@echo off
setlocal

pushd "%~dp0.."

set CONTAINER=fogonsisabors_app

docker exec %CONTAINER% php artisan storage:link

popd
endlocal
