@echo off
setlocal

pushd "%~dp0.."

php artisan storage:link

popd
endlocal
