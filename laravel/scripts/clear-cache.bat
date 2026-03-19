@echo off
setlocal

pushd "%~dp0.."

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

popd
endlocal
