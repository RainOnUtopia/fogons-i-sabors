# Fogons i Sabors

Aquest és el repositori del projecte **Fogons i Sabors**, una aplicació web desenvolupada amb Laravel i dissenyada per gestionar de manera eficient usuaris i un panell d'administració.

## ?? Tecnologies Utilitzades

El projecte utilitza un entorn modern basat en les següents tecnologies i eines:

- **Backend:** PHP 8.2, Laravel 12.x
- **Frontend:** Node.js 20, Vite, Bootstrap 5
- **Base de Dades:** MySQL 8.0
- **Eines Locals / Testing:** phpMyAdmin (Gestió de DB), Mailpit (Testing de correus)
- **Contenidors:** Docker, Docker Compose
- **Icones:** Bootstrap Icons

---

## ?? VPS

El projecte utilitza un VPS amb Ububtu Desktop 24.04.4:

- **Usuari:** iocuser
- **Password:** iocuser

---

## ?? Com Muntar l'Entorn amb Docker

El projecte està totalment preparat per ser executat en contenidors Docker, la qual cosa facilita molt la configuració de l'entorn de desenvolupament sense haver d'instal·lar manualment les dependències (PHP, MySQL, Node.js) al teu sistema.

### Estructura dels Serveis Docker
L'arxiu `docker-compose.yml` defineix els següents serveis:
- **`app`**: L'aplicació Laravel corrent sobre una imatge personalitzada (`Dockerfile`) amb PHP 8.2 i totes les extensions necessàries. S'encarrega d'instal·lar dependències de Composer, executar migracions, seeders i aixecar el servidor a `http://localhost:8000`.
- **`node`**: Contenidor amb Node 20 basat en Alpine. Aquest servei instal·la els paquets npm i inicia el servidor de Vite (`npm run dev`) per compilar els assets del frontend a `http://localhost:5173`.
- **`db`**: Base de dades MySQL 8.0 accessible externament des de l'amfitrió pel port `33060` (per evitar conflictes amb un MySQL local).
- **`phpmyadmin`**: Panell web per gestionar la base de dades disponible a `http://localhost:8080`.
- **`mailpit`**: Eina per capturar l'enviament de correus en local. Interfície web disponible a `http://localhost:8025`.

### Passos per Posar-ho en Marxa

1. **Clonar el repositori i preparar l'entorn**
   ```bash
   git clone https://github.com/RainOnUtopia/fogons-i-sabors.git
   cd fogons-i-sabors
   ```

2. **Configurar les variables d'entorn**
   Assegura't de copiar l'arxiu d'exemple de Laravel (`.env.example`) a `.env` si no s'ha fet automàticament i ajustar els paràmetres de base de dades.
   ```bash
   cp laravel/.env.example laravel/.env
   ```

3. **Inici del sistema amb Docker**
   Pots utilitzar els scripts proporcionats o llançar l'ordre de Docker manualment des de la carpeta de l'aplicació (`laravel`):
   ```bash
   cd laravel
   docker compose up -d
   ```
   Això ho automatitzarà pràcticament tot en el primer inici: s'instal·laran els paquets de Composer i de Node, s'executaran les migracions de test i s'aixecarà l'entorn.

---

## ?? Usuaris per Defecte

En executar les migracions i els *seeders* (que ja s'executen automàticament en iniciar amb Docker), es creen els següents usuaris per defecte perquè puguis provar l'aplicació des d'un inici:

- **Compte d'Administrador:**
  - **Correu:** `admin@admin.com`
  - **Contrasenya:** `12345678`
  - *Amb accés complet al panell d'administració per gestionar la plataforma i els usuaris.*

- **Compte de Prova:**
  - **Correu:** `test@example.com`
  - **Contrasenya:** `password`
  - *Usuari regular per verificar el funcionament des de la perspectiva d'un usuari estàndard.*

---

## ?? Scripts Administratius i de Desenvolupament

El directori `laravel/scripts/` conté diversos arxius d'scripting `.bat` (Windows) per facilitar el flux de treball del desenvolupador:

### Scripts per a l'Entorn Docker de desenvolupament
Si has decidit apostar exclusivament per l'entorn amb Docker, asssignant tasques als contenidors interns:
- **`Init-docker.bat`**: Alça i posa en marxa la xarxa de contenidors en segon pla executant l'ordre `docker compose up -d`.
- **`migrate-docker.bat`**: Executa de forma segura les migracions de la base de dades de Laravel (`php artisan migrate`) des de dins del contenidor de l'aplicació en un sol simple doble clic.
- **`clear-cache-docker.bat`**: Neteja pràcticament tota la memòria cau de l'aplicació (caché, de sessions, config, rutes i vistes optimitzades) directament desaltant a l'interior del contenidor de l'apliació. Estalvia haver d'introduir instruccions per consola.

### Scripts per a l'Entorn Local (Sense Dependre de Docker)
Si estàs desenvolupant sota un entorn nadiu amb PHP i Node.js/NPM instal·lats localment al teu Windows (com XAMPP/Laragon, etc.):
- **`install.bat`**: Soluciona l'arrencada automatitzant l'ordre `composer install` i `npm install` tot seguits en un únic fitxer interactiu.
- **`start-dev-servers.bat`**: Llança i manté en paral·lel de forma totalment automàtica dues consoles: una executant Vite (`npm run dev`) i l'altra el servidor de prova (`php artisan serve`). 
- L'aplicació també allotja d'altres scripts de tasca individualitzats i atòmics com **`dev.bat`**, **`serve.bat`**, **`migrate.bat`** i **`clear-cache.bat`**, que permeten executar aïlladament procediments ràpids.



### Desplegament a produccio

## 🛠️ 1. Preparació de l'Entorn (Ubuntu)

Abans de començar, hem d'assegurar-nos que el servidor té Docker i Git instal·lats. Utilitzarem l'script `setup_ubuntu.sh`.

```bash
# Executa l'script de configuració inicial
bash setup_ubuntu.sh
```

> [!IMPORTANT]
> Després de l'execució, és necessari tancar la sessió i tornar a entrar (o executar `su - $USER`) perquè els permisos de Docker s'apliquin correctament al teu usuari.

---

## 🔑 2. Configuració de Variables de producció

Abans d'aixecar els serveis, hem de configurar les claus i contrasenyes reals. El fitxer `variables_produccio.sh` és el lloc on definirem aquests valors sensibles.

```bash
# Edita el fitxer i posa les teves claus reals
# - DB_PASSWORD: Contrasenya per MySQL.
# - MAIL_PASSWORD: Contrasenya d'aplicació de Gmail.
nano variables_produccio.sh
```

Aquestes variables s'injectaran automàticament als contenidors durant el desplegament.

---

## 🚢 3. Desplegament Inicial

L'script `deploy.sh` realitza totes les passes complexes de forma automàtica:
- Clona o actualitza el repositori.
- Configura el domini `fogonsisabors.com` al fitxer `/etc/hosts` local.
- Genera certificats SSL autosignats per poder entrar via **HTTPS**.
- Construeix les imatges de Docker (`Dockerfile.prod`).
- Executa migracions, seeders i optimitzacions de Laravel.
- Repara els permisos de les carpetes `storage` i `cache`.

**Per executar-lo:**
```bash
./deploy.sh
```

Un cop finalitzat, podràs accedir a: `https://fogonsisabors.com`

---

## 🔄 4. Com Actualitzar la Producció

Quan facis canvis al codi i els pugis al repositori Git, no cal tornar a fer tot el deploy. Utilitza l'script `update_prod.sh`:

```bash
./update_prod.sh
```

Aquest script:
1. Sincronitza el teu `.env` amb les possibles novetats de `.env.prod` (sense perdre la teva `APP_KEY`).
2. Re-construeix els contenidors per agafar el codi nou.
3. Executa les noves migracions de base de dades.
4. Neteja la memòria cau per aplicar els canvis.

---

## 📁 Scripts Disponibles i la seva funció

| Script | Propòsit |
| :--- | :--- |
| `setup_ubuntu.sh` | Installa Docker, Git i configura els permisos de l'usuari. |
| `variables_produccio.sh` | Magatzem de variables sensibles (no es puja al git amb claus reals). |
| `deploy.sh` | Procés complet de posada en marxa des de zero. |
| `update_prod.sh` | Actualització ràpida del codi i la configuració. |

---

## ⚠️ Resolució de Problemes Freqüents (Troubleshooting)

### 1. El domini no carrega al navegador
Assegura't que el fitxer `/etc/hosts` té la línia:
`127.0.0.1 fogonsisabors.com`
L'script `deploy.sh` ho fa sol, però pots comprovar-ho amb `cat /etc/hosts`.

### 2. Error de permisos (Permission Denied) a logs o cache
Si l'aplicació dóna un error de "The stream or file could not be opened in append mode", és un problema de permisos. Pots forçar-los de nou dins del contenidor:
```bash
docker exec -it fogonsisabors_prod_app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
```

### 3. Error SSL "La connexió no és privada"
Com que utilitzem certificats autosignats (generats localment), el navegador et donarà un avís de seguretat. Prem a **"Avançat"** i **"Continua cap a fogonsisabors.com (no segur)"**. És perfectament normal per a aquest entorn.

### 4. Els correus no s'envien
Comprova que la contrasenya a `variables_produccio.sh` sigui una **Contrasenya d'Aplicació de Gmail**, no la teva contrasenya normal del compte.

### 5. Reiniciar des de zero
Si vols esborrar-ho tot i tornar a començar (Compte: s'esborrarà la base de dades!):
```bash
docker compose -f docker-compose.prod.yml down -v
./deploy.sh
```

### 6. Accés a la Base de Dades (phpMyAdmin)
Per defecte, phpMyAdmin està desactivat per seguretat. Per activar-lo temporalment per debug:
```bash
docker compose -f docker-compose.prod.yml --profile debug up -d
```
Estarà disponible a: `http://localhost:8080`

---
?? *Projecte construït per a l'assignatura M12.*
