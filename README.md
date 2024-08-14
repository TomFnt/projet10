# Projet Symfony 6.4.6 - TaskLinker

TaskLinker est une application web développée sous Symfony 6.4.6. ELle est destinée à la gestion de vos différentes tâches, projets et  membres de votre l'équipe.

## Mise à jour de ce projet 

Le projet tasklinker a été mis à jour afin de pouvoir rajouter les fonctionnalités suivantes : 
- Mise en place d'un système d'authentification sur le site, permettant ainsi de se créer un compte et de pouvoir se connecter sur le site Tasklinker 
- Intégration d'une gestion de rôle afin de pouvoir limiter l'accès à certains contenus selon si on est un utilisateur classique ou si on est chef de projet, mais aussi si on est assigné ou non a un projet ou une tâches. 
- Renforcement du processus d'authentification via la double authentification de Google Authentificator 

## Prérequis

Avant de pouvoir installer et exécuter ce projet, assurez-vous d'avoir les prérequis suivants installés sur votre système :

- PHP 8.1 ou supérieur
- Composer (gestionnaire de dépendances PHP)
- Serveur de base de données compatible (par exemple, MySQL, PostgreSQL)
- L'application mobile Google Authentificator d'installé 

## Installation

### 1. Installation de votre  projet symfony

Pour créer un nouveau projet Symfony 6.4.6, utilisez la commande suivante afin d'installer votre projet Symfony avec Composer :

```bash
composer create-project symfony/skeleton:"6.4.6" taskLinker
````
Maintant, vous devez vous positionnez dans le répertoire de votre projet. 

### 2. Cloner le repos Github

Afin de pouvoir récupérer directement les modifications disponnible sur le repo GitHub du projet, Initialisez un nouveau dépôt Git à la racine de votre projet Symfony en utilisant cette commande à la racine de votre projet Symfony : 
```bash
git init 
````

Ensuite, Ajoutez le repo GitHub grâce à la commande suivante : 
```bash
git remote add origin https://github.com/TomFnt/projet10.git
````

Enfin, il ne reste plus qu'à récupèrer les modifications du repoo distant directement sur votre branche main en local avec la commande suivante : 
```bash
git pull origin main
```

### 3. Installer les dépendances

Une fois les modifications du repos distant récupérés sur votre environnement. Éxecuter la commande suivante afin d'installer l'ensemble des dépendances néccessaire au fonctionnement du projet.
```bash
composer install
```

### 4. Configurer la base de données


Avant de créer la base de donnée, vous devait créer votre propre fichier ".env.local" à la racine du projet et configurez votre base de données :

```plaintext
DATABASE_URL=mysql://user:password@127.0.0.1:3306/tasklinker
```
Remplacez user et password par votre nom d'utilisateur et mot de passe de base de données.

Ensuite, exécuter les commandes suivantes afin de créer la base de donnée. 
```bash
php bin/console doctrine:database:create
```

```bash
php bin/console doctrine:migrations:migrate
```

### 7. Charger les données de test (facultatif)
```bash
php bin/console doctrine:fixtures:load
```
Cette commande ajoutera des données de test à votre base de donnée. Votre jeu test de donnée contiendra alors 5 employés, 3 projets ainsi que 8 tâches.

## Utilisation

Pour exécuter l'application, utilisez la commande Symfony CLI suivante :

```bash
symfony server:start
```
L'application sera accessible à l'adresse http://localhost:8000 dans votre navigateur.

## Inscription et connexion 

Afin de pouvoir accèder aux contenus du site, il est nécessaire de créer un compte. Après la création du compte, vous pouvez l'utiliser pour vous connecter sur TaskLinker. 

Cependant, il sera nécessaire d'utiliser l'application Google Authentificator afin de pouvoir valider la double authentification. Si c'est votre première connexion sur Tasklinker, il vous suffit de scanner le QR code qui s'affiche sur la page de connexion, grâce à l'application Google Authentificator, puis de saisir le code à 6 chiffres qui s'affiche sur l'application. 
Pour les prochaines connexions, vous n'aurez plus qu'à ouvrir Google Authentificator afin de récupèrer le code à 6 chiffres.  