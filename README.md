# Projet Symfony 6.4.6 - TaskLinker

TaskLinker est une application web développée sous Symfony 6.4.6. ELle est destinée à la gestion de vos différentes tâches, projets et  membres de votre l'équipe .

## Prérequis

Avant de pouvoir installer et exécuter ce projet, assurez-vous d'avoir les prérequis suivants installés sur votre système :

- PHP 8.1 ou supérieur
- Composer (gestionnaire de dépendances PHP)
- Serveur de base de données compatible (par exemple, MySQL, PostgreSQL)

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
git remote add origin https://github.com/TomFnt/projet8-exo2.git
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