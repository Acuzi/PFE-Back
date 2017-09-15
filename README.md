HTTP_GAME
=========

A Symfony project created on November 1, 2016, 1:45 pm.

Installation
------------
1. Installer [Symfony](http://symfony.com/doc/current/setup.html) et [Composer](http://symfony.com/doc/current/setup/composer.html).

2. Installer les dépendances :
`$ composer install`

3. Pour faire fonctionner les tests, PHPUnit doit être installé.
Après avoir configuré l’accès à la base de données par Doctrine, il faut
charger le scénario de test dans la base de données. Pour cela, il suffit de
taper à la suite les deux commandes :
`$ php bin/console doctrine:schema:update –force`
`$ php bin/console doctrine:fixtures:load`
Afin de créer le schéma puis de persister les données du scénario.

Tests fonctionnels
------------------
[Phpunit](https://phpunit.de/manual/current/en/installation.html#installation.phar) est requis.
Pour lancer les tests, se placer dans le répertoire racine et exécuter la commande :
 `$ phpunit`
Le serveur n'a pas besoin d'être démarré pour lancer les tests.

Démarrer le serveur
-------------------
Pour démarrer le serveur de debug, utiliser la commande :
`$ php bin/console server:start`
