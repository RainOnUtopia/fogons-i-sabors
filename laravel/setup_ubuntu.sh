#!/bin/bash
set -e

echo "======================================================="
echo " Instal·lació de dependències per Ubuntu Desktop"
echo " Fogons i Sabors - Preparació de l'entorn màquina"
echo "======================================================="

echo ">> Pas 1: Actualitzant el sistema..."
sudo apt-get update
sudo apt-get upgrade -y

echo ">> Pas 2: Instal·lant dependències bàsiques (Git, Curl, etc)..."
sudo apt-get install -y ca-certificates curl gnupg git

echo ">> Pas 3: Preparant els repositoris oficials de Docker..."
sudo install -m 0755 -d /etc/apt/keyrings
if [ ! -f /etc/apt/keyrings/docker.gpg ]; then
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    sudo chmod a+r /etc/apt/keyrings/docker.gpg
fi

echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

echo ">> Pas 4: Instal·lant Docker Engine i Docker Compose v2..."
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

echo ">> Pas 5: Configurant l'usuari actual perquè pugui executar Docker sense sudo..."
sudo usermod -aG docker $USER

echo "======================================================="
echo " INSTAL·LACIÓ FINALITZADA AMB ÈXIT"
echo " IMPORTANT: Perquè els canvis de grup de Docker tinguin efecte,"
echo " has de tancar la sessió del teu usuari (logout) i tornar a entrar,"
echo " o bé executar la següent comanda al terminal ara mateix:"
echo ""
echo "   su - $USER"
echo ""
echo " Després d'això ja pots executar el teu deploy.sh!"
echo "======================================================="
