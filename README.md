# In progress

# Creation of a blog from A to Z (CMS WORDPRESS) with Symfony 6.3.*, ZenBloggy | Multipurpose Bootstrap 5 HTML Template  (Dark/Light) Version 1.2.0
# Création d'un blog de A à Z (CMS WORDPRESS) avec Symfony 6.3.*, ZenBloggy | Multipurpose Bootstrap 5 HTML Template  (Dark/Light) Version 1.2.0

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

```sh
doker-compose up -d
symfony serve -d
```

## Run tests
## Exécuter des tests

```sh
php bin/phpunit --testdox
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