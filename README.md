# In progress

# Creation of a blog from A to Z (CMS WORDPRESS) with Symfony 6.3.*, ZenBloggy | Multipurpose Bootstrap 5 HTML Template  (Dark/Light) Version 1.4.0
# Création d'un blog de A à Z (CMS WORDPRESS) avec Symfony 6.3.*, ZenBloggy | Multipurpose Bootstrap 5 HTML Template  (Dark/Light) Version 1.4.0

## Development environment
## Environnement de développement

### Pre-requisite
### Prérequis
* PHP 8.2
* Composer
* Symfony CLI
* Docker
* Docker Compose

You can check the prerequisites (except Docker and Docker Compose) with the following command (from Symfony CLI) :
Vous pouvez vérifier les prérequis (sauf Docker et Docker Compose) avec la commande suivante (depuis Symfony CLI) :

```sh
symfony check:requirements
```

### Launch Development Environment
### Lancer l'environnement de développement

Next:
A) Run docker-compose up -d database to start your database container
or ```sh docker-compose up -d ``` to start all of them.

B) Run symfony serve -d to start your server
```sh symfony serve -d ``` to start

C) Run docker-compose stop will stop all the containers in docker-compose.yaml.
```sh docker-compose down ``` will stop and destroy the containers.

D) Run symfony serve:stop to stop your server
```sh symfony serve:stop ``` to stop


## Run tests
## Exécuter des tests

```sh
php bin/phpunit --testdox
```

### Envoie des mails de Contacts
### Send emails from Contacts

Les emails de contact sont stockés en BDD, pour les envoyer à l'admin par email, il faut mettre en place un cron sur :
The contact emails are stored in BDD, to send them to the admin by email, you must set up a cron on :

```sh
symfony console app:send-contact
```

⚙️ Installation

--------------
## Install the PHP dependencies and JS dependencies.
## Installez les dépendances PHP et les dépendances JS.
```sh
composer install
```
```sh
npm install
```
## Installing assets
## Installation des ressources
```sh
npm run dev
```