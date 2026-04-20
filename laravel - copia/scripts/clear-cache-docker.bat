@echo off
setlocal

REM Executa clear cache dins el contenidor Docker de l'app Laravel
set CONTAINER=fogonsisabors_app

docker exec %CONTAINER% php artisan cache:clear
docker exec %CONTAINER% php artisan config:clear
docker exec %CONTAINER% php artisan route:clear
docker exec %CONTAINER% php artisan view:clear

endlocal
