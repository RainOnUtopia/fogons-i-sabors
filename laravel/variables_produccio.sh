#!/bin/bash

# =========================================================================
# VARIABLES DE PRODUCCIÓ - FOGONS I SABORS
#
# Aquest fitxer s'utilitza abans de fer un desplegament per passar 
# valors "sensibles" o reals l'entorn de Docker sense haver d'escriure'ls
# directament a l'arxiu .env o docker-compose.
#
# Modifica els valors del dret pel teu entorn real.
# =========================================================================

# 1. Base de dades
# Substitueix al valor que vulguis que tingui l'usuari 'fogonsisabors' 
# a la base de dades MySQL de producció.
export DB_PASSWORD="fogons_contrasenya_forta_123!"

# 2. Correu Electrònic (Gmail)
# Utilitzat per poder enviar correus. Com que has demanat fer-ho via Gmail,
# introdueix la "contrasenya d'aplicació" aquí directament.
export MAIL_PASSWORD="teva_contrasenya_d_aplicacio_aqui_sense_espais"

# Podries afegir més variables aquí en el futur, per exemple:
# export APP_URL="https://altredomini.cat"
