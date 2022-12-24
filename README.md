# La Nimes Alerie - Business case

## Introduction

Création individuelle d'une boutique en ligne fonctionelle from scratch.  
L’objectif principal du projet est de pouvoir proposer une  
plateforme fonctionnelle.  

La plateforme est divisée en trois parties :  
* Le front office du e-commerce
* Le back office du e-commerce
* Le dashboard d’analyse de datas

Comme tout e-commerce, il est possible pour l’utilisateur de se connecter, de  
s’inscrire mais aussi de faire une demande de renvoi de mot de passe.  
L’authentification est sécurisée avec token et l’intégralité des mots de passes sont hashés.  

La partie frontend (front office et back office) du projet est réalisée avec  
[Symfony](https://symfony.com/) et [API Platform](https://api-platform.com/).  

## Technologies utilisées

* Symfony (5.4.12)
* PHP (8.0)
* MySQL (5.1.1)

## Installation

Cloner le dépôt sur votre machine :  

```git clone https://github.com/LaurentCNS/La-nimes-alerie-Business-case```

Creer un fichier `.env.local` à la racine et configurez vos propres informations de connexion de votre base de données.  
Vous pouvez consulter le fichier `.env` pour consulter les informations par défaut.  

La ligne à copier et à modifier sur votre fichier `.env.local` est :  

```DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"```

Verifier la version de php installée sur votre machine (8.0.2) :

```php -v```

Installer les dépandances symfony  :  

```symfony composer install```

Si vous avez une erreur sodium, rajouter dans php.ini: extension=php_sodium.dll  
Si vous êtes dans la bonne version de php mais que vous avez quand même une erreur: composer config platform.php 8.0.2

```yarn install```


Creer la base de données :  

```symfony console doctrine:database:create```

Faire les migrations sur la bdd :  

```symfony console doctrine:migrations:migrate```

Envoyer les données de test des fixtures :  

```php bin/console hautelook:fixtures:load```  

Si vous avez une erreur de memory limit, modifier dans php.ini: memory_limit = 128M en 512M   

Pour purger la base avec d'autres fixtures :  

```symfony console doctrine:fixtures:load --purge-with-truncate```

Vous aurez besoin d'une clé pour l'authentification JWT pour l'API REST.



## Lancer le projet

Lancer le serveur symfony :  

```yarn watch```

```symfony serve```

Utiliser l'url indiqué par le serveur pour vous rendre sur la page d'accueil.  

Pour vous rendre sur api-platform, rajouter ceci après l'url:  

```/api/docs```