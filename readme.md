# Développement par les tests

## Rappels

<!-- Les types de tests -->
- tests unitaires
- tests fonctionnels
- tests end to end (e2e) (de bout en bout)

<!-- Les objectifs de tests -->
- tests intégrité
- tests de non regression

## Mise en place PHP

Le projet contient maintenant une base de travail pour PHP avec :

- `composer.json` pour la gestion des dependances ;
- `phpunit.xml` pour la configuration des tests ;
- `src/` pour le code applicatif ;
- `tests/Unit/` pour les tests unitaires.

## Commandes utiles

Installer les dependances :

```bash
composer install
```

Lancer tous les tests :

```bash
composer test
```

Lancer uniquement les tests unitaires :

```bash
composer test:unit
```

