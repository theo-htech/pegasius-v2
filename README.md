# O.do.P (Opening do Power)

[![Testing Symfony](https://github.com/theo-htech/o.do.p/actions/workflows/symfony.yml/badge.svg?branch=develop)](https://github.com/theo-htech/o.do.p/actions/workflows/symfony.yml)

## Introduction

Bienvenue à notre projet Symfony ! Ce projet lance une nouvelle perspective sur la relation entre les managers et leurs équipes, en mettant l'accent sur la marque employeur. En utilisant cet outil, nous espérons faciliter et améliorer l'interaction entre les managers et leurs équipes tout en mettant en avant la marque employeur.

## Caractéristiques

Voici quelques-unes des caractéristiques principales que notre application offre :

- **Communication des managers** : Nous offrons des outils dédiés pour aider les managers à communiquer facilement avec leurs équipes.
- **Marque employeur** : Mettez en valeur la culture de votre entreprise et la marque employeur à travers diverses fonctionnalités intégrées.

## Prérequis 

Pour profiter de ce projet, vous aurez besoin de :

- PHP 8.1 ou une version ultérieure.
- Symfony v6.4.4.
- Composer, pour gérer les dépendances PHP.

## Installation

Suivez les étapes suivantes pour installer et configurer ce projet sur votre ordinateur local :

1. Clonez ce dépôt sur votre machine locale.
2. Allez dans le répertoire du projet cloné et exécutez `composer install` pour télécharger les dépendances du projet.
3. Copiez le fichier `.env.example` vers un nouveau fichier `.env` et configurez les variables d'environnement appropriées pour votre configuration locale, y compris les détails de la base de données.
4. Exécutez `php bin/console doctrine:database:create` pour créer la base de données, puis `php bin/console doctrine:migrations:migrate` pour appliquer les migrations.
5. Enfin, exécutez `symfony server:start` pour démarrer le serveur intégré de Symfony.

## Licence

Ce projet n'est pas un logiciel libre. Tous les droits sont réservés. Pour plus d'informations sur l'utilisation de ce projet, veuillez nous contacter.
