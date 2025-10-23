# 🧠 SymfonAI

**SymfonAI** est un environnement de développement Symfony enrichi d’outils d’**intelligence artificielle** pour assister le développeur dans la génération, la validation et la correction de code.  
Il vise à combiner la **rigueur du framework Symfony** avec la **souplesse des modèles d’IA locaux ou distants** (comme Ollama, OpenAI, Mistral, etc.).

---

## 🚀 Objectifs

- Intégrer des **assistants IA** dans le flux de travail Symfony.
- Offrir une **aide contextuelle au codage**, au test et à la documentation.
- Rester **libre, auto-hébergeable et extensible**.
- S’appuyer sur des standards **PSR**, **Symfony Flex** et des outils **dev-friendly** (PHPStan, CS Fixer, etc.).

---

## 📦 Installation

### Prérequis

- PHP ≥ **8.3**
- Composer ≥ **2.6**
- Symfony CLI (optionnel mais recommandé)

### Installation du projet

```bash
git clone https://github.com/mazarini/symfonai.git
cd symfonai
composer install
```

Symfony Flex configurera automatiquement les recettes nécessaires (`.gitignore`, structure de répertoires, etc.).

---

## ⚙️ Configuration du projet

### Fichiers de base

| Fichier                  | Rôle                                             |
| ------------------------ | ------------------------------------------------ |
| `.php-cs-fixer.dist.php` | Règles de style et normalisation de code         |
| `phpstan.neon`           | Analyse statique stricte (niveau 9)              |
| `composer.json`          | Dépendances et métadonnées du projet             |
| `.gitignore`             | Exclusions pour Git (cache, vendor, build, etc.) |

---

## 🧰 Outils de qualité

### 🧩 PHPStan

Analyse statique pour détecter les erreurs et incohérences avant exécution.

```bash
vendor/bin/phpstan analyse
```

> Configuré pour le **niveau 9**, avec des extensions Symfony et des règles strictes.

### 🎨 PHP-CS-Fixer

Uniformise la mise en forme du code selon les standards Symfony, PSR-12 et GPL.

```bash
vendor/bin/php-cs-fixer fix
```

> Le cache est stocké dans `var/cache/.php-cs-fixer`.

### 🧪 Tests

Les tests PHPUnit seront situés dans `tests/`.  
Exemple de commande :

```bash
vendor/bin/phpunit
```

---

## 🧠 Intégration IA (prévue)

SymfonAI vise à proposer des **outils IA intégrés** dans le workflow Symfony :

- Exécution de requêtes locales via **Ollama**
- Suggestions de code ou documentation via **agents IA**
- Validation de logique métier par analyse contextuelle

> Une compatibilité future avec `symfony/ai` est prévue pour bénéficier d’une API unifiée.

---

## 🔍 Structure du projet

```
symfonai/
├── src/                # Code source principal
├── tests/              # Tests unitaires
├── config/             # Configuration Symfony
├── var/                # Cache PHP-CS-Fixer, Symfony, etc.
├── vendor/             # Dépendances Composer
└── public/             # Point d’entrée web
```

---

## 🪶 Commandes utiles

| Commande           | Description                                  |
| ------------------ | -------------------------------------------- |
| `composer fix`     | Applique les règles de style automatiquement |
| `composer phpstan` | Exécute PHPStan (analyse statique)           |
| `composer cover`   | Lance PHPUnit                                |

---

## 📖 Licence

SymfonAI est distribué sous licence **GNU GPL v3**.  
Vous êtes libre de l’utiliser, modifier et redistribuer selon les termes de cette licence.

Consultez le fichier [`LICENSE`](LICENSE) pour plus de détails.

---

## 👤 Auteur

**Mazarini**  
📧 [mazarini@pm.me](mailto:mazarini@pm.me)  
💻 [github.com/mazarini](https://github.com/mazarini)

---

## 💡 Inspirations et crédits

- [Symfony](https://symfony.com)
- [PHPStan](https://phpstan.org)
- [PHP-CS-Fixer](https://cs.symfony.com)
- [Ollama](https://ollama.ai) / [OpenAI](https://openai.com)

---

> _“Le but n’est pas que l’IA remplace le développeur, mais qu’elle l’aide à devenir plus efficace, plus rapide et plus rigoureux.”_ 🧩
