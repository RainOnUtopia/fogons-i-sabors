#!/bin/bash
set -e

echo "======================================================="
echo "  Actualització de Producció - Fogons i Sabors"
echo "======================================================="

# Si es vol fer pull automàtic en executar l'script, es pot descomentar això:
# BRANCH="main"
# echo ">> 1. Obtenint últims canvis del repositori (git pull)..."
# git pull origin $BRANCH

echo ">> 1. Actualitzant .env des de .env.prod (preservant APP_KEY si existeix)..."
if [ -f .env ]; then
    # Guardem la APP_KEY actual per no perdre les sessions ni dades encriptades del sistema
    CURRENT_KEY=$(grep "^APP_KEY=" .env || true)
    
    cp .env.prod .env
    
    # Restaurem la APP_KEY al fitxer .env nou
    if [ ! -z "$CURRENT_KEY" ]; then
        sed -i "s|^APP_KEY=.*|$CURRENT_KEY|" .env
    fi
else
    cp .env.prod .env
fi

echo ">> 2. Desmuntant els contenidors de Docker antics..."
docker compose -f docker-compose.prod.yml down

echo ">> Esborrant el volum temporal de assets públics per forçar la seva actualització..."
docker volume rm fogons_public_assets || true

echo ">> 3. Reconstruint i iniciant els contenidors (Aplicarà els nous canvis al codi i configuració)..."
docker compose -f docker-compose.prod.yml up -d --build

echo ">> Esperarem 45 segons a l'inici segur del servei de Base de Dades MySQL..."
sleep 45

echo ">> 4. Executant procediments estructurals del Laravel..."

# Si no s'havia generat mai la clau o veníem de zero
if ! grep -q "^APP_KEY=base64" .env; then
    echo ">> Generant nova clau encriptada App Key..."
    docker compose -f docker-compose.prod.yml exec -T app php artisan key:generate --force
fi

echo ">> Carregant noves Migracions i recarregant Seeders a la BD..."
docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force --seed

echo ">> Esborrant memòria cau i aplicant els nous valors del .env..."
docker compose -f docker-compose.prod.yml exec -T app php artisan optimize:clear
docker compose -f docker-compose.prod.yml exec -T app php artisan optimize

echo ">> REPARACIÓ DE PERMISOS: Assignem propietat a www-data de les carpetes que han de ser modificables..."
docker compose -f docker-compose.prod.yml exec -T app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache


echo ""
echo "======================================================="
echo " ACTUALITZACIÓ COMPLETADA AMB ÈXIT"
echo " Tots els canvis de .env.prod s'han aplicat i l'entorn"
echo " docker s'ha regenerat agafant el codi més recent."
echo "======================================================="
