@echo off
setlocal

pushd "%~dp0.."

call docker compose up -d

popd
endlocal
