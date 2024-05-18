Configuration de l'environnement avec docker-compose.yaml

Installer docker selon votre OS

Lancer la commande docker-compose up

Pour générer le certificat SSL, exécuter la commande suivante : openssl req -x509 -new -out mycert.crt -keyout mycert.key -days 365 -newkey rsa:4096 -sha256 -nodes

Configurer le fichier .env avec les informations du conteneur mysql

Installer les les dépendances de symfony (composer install)

Créer la base de données via doctrine avec la commande symfony console d:m:m

Accéder au conteneur phpMyAdmin sur le port :8899

Insérer en base de données le contenu des fichiers .sql pour remplir la table dictionary, qui contiendra alors les mots à deviner

Utilisez l'application :

Sur le conteneur php sur le port :8080 (http) ou bien le port :8443 (https)

Ou via le serveur de symfony avec la commande symfony server:start
