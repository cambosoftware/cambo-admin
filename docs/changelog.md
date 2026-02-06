# Changelog

Tous les changements notables de ce projet sont documentes dans ce fichier.

Le format est base sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhere au [Semantic Versioning](https://semver.org/lang/fr/).

## [Unreleased]

### Ajoute
- Documentation interactive avec VuePress
- Tests unitaires complets (82 tests)
- GitHub Actions pour CI/CD

## [1.0.0] - 2024-XX-XX

### Ajoute

#### Core
- Package Composer `cambosoftware/cambo-admin`
- Service Provider avec auto-discovery
- Facade `Cambo` pour acces simplifie
- Configuration complete via `config/cambo-admin.php`

#### Authentification
- Login/Logout avec sessions
- Inscription avec validation
- Authentification a deux facteurs (2FA)
- Reinitialisation du mot de passe
- Verification d'email
- Gestion des sessions actives

#### Roles & Permissions
- Modele Role avec relations
- Modele Permission avec groupes
- Trait HasRoles pour les utilisateurs
- Middleware CheckRole
- Middleware CheckPermission
- Super Admin avec toutes les permissions

#### Notifications
- Systeme de notifications temps reel
- Stockage en base de donnees
- Marquage lu/non-lu
- Suppression automatique

#### Journal d'activite
- Trait LogsActivity
- Tracage des actions CRUD
- Filtrage par type et periode
- Export des logs

#### Dashboard
- Widgets personnalisables
- Statistiques en temps reel
- Graphiques avec Chart.js
- Carte geographique

#### Gestionnaire de fichiers
- Upload de fichiers
- Organisation en dossiers
- Preview des images
- Gestion des permissions

#### Parametres
- Modele Setting dynamique
- Interface de configuration
- Cache automatique
- Groupes de parametres

#### Import/Export
- Export CSV, Excel, PDF
- Import avec validation
- Preview avant import
- Mapping des colonnes

#### Internationalisation
- Support multi-langues
- Detection automatique de la langue
- Support RTL (arabe, hebreu)
- Traduction des interfaces

#### Themes
- Mode sombre/clair/systeme
- Themes personnalisables
- Variables CSS dynamiques
- Preview en temps reel

#### CLI
- `cambo:install` - Installation interactive
- `cambo:crud` - Generateur CRUD
- `cambo:page` - Generateur de pages
- `cambo:component` - Generateur de composants
- `cambo:add` - Ajout de modules

#### Composants Vue (134+)
- UI de base (Button, Badge, Avatar, etc.)
- Formulaires (Input, Select, Checkbox, etc.)
- Navigation (Tabs, Breadcrumb, etc.)
- Feedback (Alert, Toast, Modal, etc.)
- Data Display (Table, Card, etc.)

### Modifie
- N/A (premiere version)

### Obsolete
- N/A (premiere version)

### Retire
- N/A (premiere version)

### Securite
- Protection CSRF sur tous les formulaires
- Validation des entrees utilisateur
- Hashage des mots de passe avec bcrypt
- Middleware d'authentification

## Types de changements

- **Ajoute** pour les nouvelles fonctionnalites
- **Modifie** pour les changements aux fonctionnalites existantes
- **Obsolete** pour les fonctionnalites qui seront supprimees
- **Retire** pour les fonctionnalites supprimees
- **Corrige** pour les corrections de bugs
- **Securite** pour les vulnerabilites corrigees
