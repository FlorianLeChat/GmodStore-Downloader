# 📥 GmodStore Downloader

## In French

### Introduction

Ce petit site Internet permet le téléchargement d'addons en provenance du [GmodStore](https://www.gmodstore.com/) sans passer le site en ligne mais grâce à son [API](https://docs.pivity.com/). Cela est très utile dans le cas où un propriétaire d'un compte souhaite donner la possibilité à des personnes tierces de télécharger ses addons créés/achetés sans leur donner des identifiants de connexion, c'est un moyen équivalent aux solutions des « **accès secondaires** ». Le propriétaire du compte doit seulement générer un jeton d'authentification avec certaines permissions restreintes afin de le donner aux personnes autorisées.

Les jetons peuvent être générés à cette adresse : https://www.gmodstore.com/settings/personal-access-tokens. Ils doivent comporter les autorisations suivantes : `products:read`, `product-versions:read`, `product-versions:download`, `users:read` et `user-purchases:read`. Une fois créés, le site vous indique la démarche à suivre.

### Installation

> [!WARNING]
> Le déploiement en environnement de production (**avec ou sans Docker**) nécessite un serveur Web déjà configuré comme [Nginx](https://nginx.org/en/), [Apache](https://httpd.apache.org/) ou [Caddy](https://caddyserver.com/) pour servir les scripts PHP.

- Installer [PHP LTS](https://www.php.net/downloads.php) (>8.1 ou plus) ;
- Utiliser un serveur Web pour servir les scripts PHP et les fichiers statiques.

> [!TIP]
> Pour tester le projet, vous *pouvez* également utiliser [Docker](https://www.docker.com/). Une fois installé, il suffit de lancer l'image Docker de développement à l'aide de la commande `docker compose up --detach --build`. Le site devrait être accessible à l'adresse suivante : http://localhost/. Si vous souhaitez travailler sur le projet avec Docker, vous devez utiliser la commande `docker compose watch --no-up` pour que vos changements locaux soient automatiquement synchronisés avec le conteneur. 🐳

> [!CAUTION]
> L'image Docker *peut* également être déployée en production, mais cela **nécessite des connaissances approfondies pour déployer, optimiser et sécuriser correctement votre installation**, afin d'éviter toute conséquence indésirable. ⚠️

*Ce site Internet n'est en aucun cas affilié à GmodStore, à l'exception du fait que j'utilise leur formidable API pour vous fournir ce service.*

## In English

### Introduction

This simple website provides the possibility to download addons from the [GmodStore](https://www.gmodstore.com/) without going through the online website but using its [API](https://docs.pivity.com/). This is very useful in case an account owner wants to give the access to third parties to download his created/purchased addons without giving them login credentials, it is a equivalent to the "**secondary access**" way. The account owner only needs to generate an authentication token with some restricted permissions in order to give it to authorized persons.

Tokens can be generated at this address: https://www.gmodstore.com/settings/personal-access-tokens. They must have the following permissions: `products:read`, `product-versions:read`, `product-versions:download`, `users:read` and `user-purchases:read`. Once created, the site tells you what to do.

### Setup

> [!WARNING]
> Deployment in a production environment (**with or without Docker**) requires a pre-configured web server such as [Nginx](https://nginx.org/en/), [Apache](https://httpd.apache.org/), or [Caddy](https://caddyserver.com/) to serve PHP scripts.

- Install [PHP LTS](https://www.php.net/downloads.php) (>8.1 or higher) ;
- Use a web server to serve PHP scripts and static files.

> [!TIP]
> To try the project, you *can* also use [Docker](https://www.docker.com/) installed. Once installed, simply start the development Docker image with `docker compose up --detach --build` command. The website should be available at http://localhost/. If you want to work on the project with Docker, you need to use `docker compose watch --no-up` to automatically synchronize your local changes with the container. 🐳

> [!CAUTION]
> The Docker image *can* also be deployed in production, but **this requires advanced knowledge to properly deploy, optimize, and secure your installation**, in order to avoid any unwanted consequences. ⚠️

*This website is in no way affiliated with GmodStore, except that I use their amazing API to provide you this service.*

![image](https://user-images.githubusercontent.com/26360935/190854337-559ea766-dc34-4b49-b9bb-f3f69399f92d.png)