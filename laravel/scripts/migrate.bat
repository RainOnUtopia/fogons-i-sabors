@echo off
setlocal

pushd "%~dp0.."

php artisan migrate

popd
endlocal
