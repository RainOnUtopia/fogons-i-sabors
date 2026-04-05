@echo off
setlocal

REM Executa clear cache dins el contenidor Docker de l'app Laravel
set CONTAINER=fogonsisabors_app


pushd "%~dp0.."

docker exec %CONTAINER% php artisan migrate


popd
endlocal
