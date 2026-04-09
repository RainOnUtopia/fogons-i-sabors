#!/bin/bash
set -e

echo "======================================================="
echo "  Deploy a Producció - Fogons i Sabors"
echo "======================================================="

PROJECT_DIR="fogons-i-sabors"
REPO_URL="https://github.com/RainOnUtopia/fogons-i-sabors.git"
DOMAIN="fogonsisabors.com"
BRANCH="main"

# 1. Obtenir codi
if [ -d "$PROJECT_DIR" ]; then
    echo ">> El directori ja existeix. Actualitzant repositori..."
    cd $PROJECT_DIR
    git pull origin $BRANCH
else
    echo ">> Clonant repositori..."
    git clone -b $BRANCH --single-branch $REPO_URL $PROJECT_DIR
    cd $PROJECT_DIR
fi

# 2. Modificació local del servidor per adreçar el domini a local
if ! grep -q "$DOMAIN" /etc/hosts; then
    echo ">> Modificant el /etc/hosts per redirigir $DOMAIN (requereix sudo)..."
    sudo bash -c "echo '127.0.0.1 $DOMAIN www.$DOMAIN' >> /etc/hosts"
else
    echo ">> El domini ja existeix a /etc/hosts."
fi

# A partir d'aquí tot s'executa a la carpeta laravel
cd laravel

# 3. Preparació inicial de l'entorn de producció i seguretat
if [ ! -f .env ]; then
    echo ">> Copiant .env.prod a la ruta del .env de producció..."
    cp .env.prod .env
    # Caldrà afegir la clau mestra del laravel!
else
    # Guardem la APP_KEY actual per no perdre les sessions ni dades encriptades del sistema
    CURRENT_KEY=$(grep "^APP_KEY=" .env || true)
    
    cp .env.prod .env
    
    # Restaurem la APP_KEY al fitxer .env nou
    if [ ! -z "$CURRENT_KEY" ]; then
        sed -i "s|^APP_KEY=.*|$CURRENT_KEY|" .env
    fi
fi

# 4. Creació dels certificats SSL autosignats globals a l'equip Ubuntu
SSL_DIR="nginx/ssl"
if [ ! -d "$SSL_DIR" ]; then
    echo ">> Generant certificats SSL autosignats per Nginx..."
    mkdir -p $SSL_DIR
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout $SSL_DIR/nginx.key -out $SSL_DIR/nginx.crt \
        -subj "/C=ES/ST=Catalunya/L=Barcelona/O=Fogons/CN=$DOMAIN"
else
    echo ">> Els certificats SSL ja estan creats correctament."
fi

# Càrrega de variables personalitzades de producció (contrasenyes, corrents, etc)
if [ -f "variables_produccio.sh" ]; then
    echo ">> Carregant dades personals des de variables_produccio.sh..."
    source variables_produccio.sh
fi

# 5. Iniciar serveis
echo ">> Desplegant i construint imatges docker de Producció... (pot trigar una mica)"
docker volume rm fogons_public_assets 2>/dev/null || true
docker compose -f docker-compose.prod.yml up -d --build

echo ">> Esperarem 15 segons a l'inici total i absolut del servei MySQL de fons..."
sleep 15

# 6. Comandes inicials del laravel en cas que vingui buit: Generarem la KEY, Migrations amb Seeder i l'enllaç de fotos per que es vegin els usuaris
echo ">> Passant procediments estructurals del Laravel..."

if ! grep -q "^APP_KEY=base64" .env; then
    echo ">> Generant clau encriptada App Key de producció..."
    docker compose -f docker-compose.prod.yml exec -T app php artisan key:generate --force
fi

echo ">> Executant Migracions i Seeders a la BD..."
docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force --seed

echo ">> Enllaçant carpetes fixes a l'arxiu d'emmagatzematge del PHP..."
docker compose -f docker-compose.prod.yml exec -T app php artisan storage:link || true

echo ">> Optimitzant cau i recursos..."
docker compose -f docker-compose.prod.yml exec -T app php artisan optimize:clear
docker compose -f docker-compose.prod.yml exec -T app php artisan optimize

echo ">> REPARACIÓ DE PERMISOS: Assignem propietat a www-data de les carpetes que han de ser modificables..."
docker compose -f docker-compose.prod.yml exec -T app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo ""
echo "======================================================="
echo " DESPLEGAMENT ACABAT AMB ÈXIT"
echo " L'Adreça d'entrada és:"
echo " 👉 https://$DOMAIN/"
echo ""
echo " IMPORTANT: Si s'utilitaran els correus, recordeu entrar a la configuració"
echo " manual a '$PROJECT_DIR/laravel/.env' dins de la secció de correu (MAIL_) per configurar la contrasenya final."
echo "======================================================="
