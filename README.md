# 📥 GmodStore Downloader

![HTML](.gitlab/badges/html.svg)
![CSS](.gitlab/badges/css.svg)
![TypeScript](.gitlab/badges/typescript.svg)

![Svelte](.gitlab/badges/svelte.svg)
![TailwindCSS](.gitlab/badges/tailwindcss.svg)
![Vite](.gitlab/badges/vite.svg)
![Vitest](.gitlab/badges/vitest.svg)
![Prettier](.gitlab/badges/prettier.svg)
![ESLint](.gitlab/badges/eslint.svg)

## In French

> [!IMPORTANT]
> Depuis mars 2026, le code du projet est désormais hébergé sur mon instance GitLab personnalisée, accessible à [cette adresse](https://git.florian-dev.fr/floriantrayon/GmodStore-Downloader). Le dépôt GitHub est un miroir du dépôt GitLab, **mis à jour automatiquement**.
>
> **Les contributions publiques restent sur GitHub et sont les bienvenues** ; les pull requests validées y seront ensuite transférées manuellement sur GitLab pour être intégrées. 🙂

### Introduction

Ce petit site Internet permet de télécharger des addons depuis le [GmodStore](https://www.gmodstore.com/) sans passer par l’interface en ligne, mais en s'appuyant sur son [API](https://docs.pivity.com/). Cette solution est particulièrement utile lorsqu'un propriétaire de compte souhaite offrir à des tiers la possibilité de télécharger ses addons créés ou achetés, sans leur communiquer ses identifiants de connexion. C'est une alternative sécurisée aux « **accès secondaires** ». Le propriétaire doit simplement générer un jeton d'accès avec des permissions limitées, qu'il pourra ensuite partager avec les personnes autorisées.

Les jetons peuvent être générés à cette adresse : https://www.gmodstore.com/settings/personal-access-tokens. Ils doivent comporter les autorisations suivantes : `products:read`, `product-versions:read`, `product-versions:download`, `users:read` et `user-purchases:read`. Une fois créés, le site Internet vous indique la démarche à suivre.

Auparavant, ce projet était développé en [PHP](https://www.php.net/) 🐘 (disponible via la branche `no-svelte`), car l'API GmodStore avait restreint les [en-têtes CORS](https://developer.mozilla.org/fr/docs/Web/HTTP/Guides/CORS), empêchant toute communication directe depuis un navigateur. Depuis, cette restriction a été levée, ce qui permet désormais d'utiliser le *framework* [Svelte](https://svelte.dev/) 🔥 pour interagir **directement** avec leur API. Cette migration a supprimé la nécessité d'un serveur intermédiaire, améliorant ainsi la confidentialité des données en exécutant l'**intégralité** du site Internet côté client, tout en optimisant ses performances.

> [!NOTE]
> Tout ou partie du code peut contenir des commentaires dans ma langue natale (le français) afin de faciliter le développement. 🌐

### Installation

> [!WARNING]
> Le déploiement en environnement de production nécessite un serveur Web déjà configuré comme [Nginx](https://nginx.org/en/), [Apache](https://httpd.apache.org/) ou [Caddy](https://caddyserver.com/) pour servir les fichiers statiques générés par Vite. ⚠️

#### Développement local

- Installer [NodeJS LTS](https://nodejs.org/) (>20 ou plus) ;
- Installer les dépendances du projet avec la commande `npm install` ;
- Démarrer le serveur local Vite avec la commande `npm run dev`.

#### Déploiement en production

- Installer [NodeJS LTS](https://nodejs.org/) (>20 ou plus) ;
- Installer les dépendances du projet avec la commande `npm install` ;
- Compiler les fichiers statiques du site Internet avec la commande `npm run build` ;
- Utiliser un serveur Web pour servir les fichiers statiques générés à l'étape précédente.

*Ce site Internet n'est en aucun cas affilié à GmodStore, à l'exception du fait que j'utilise leur formidable API pour vous fournir ce service.*

## In English

> [!IMPORTANT]
> Since March 2026, the project's code has been hosted on my custom GitLab instance, available at [this address](https://git.florian-dev.fr/floriantrayon/GmodStore-Downloader). The GitHub repository is a mirror of the GitLab repository, **automatically kept up to date**.
>
> **Public contributions remain on GitHub and are welcome**; validated pull requests will then be manually transferred to GitLab to be integrated. 🙂

### Introduction

This small website lets you download addons from the [GmodStore](https://www.gmodstore.com/) without using the online interface, but by relying on its [API](https://docs.pivity.com/). This is particularly useful when an account owner wants to offer third parties a way to download created or purchased addons, without providing personal credentials. It's a secure alternative to "**secondary access**". The owner simply needs to generate an access token with limited permissions, which can then be shared with authorized persons.

Tokens can be generated at this address: https://www.gmodstore.com/settings/personal-access-tokens. They must have the following permissions: `products:read`, `product-versions:read`, `product-versions:download`, `users:read` and `user-purchases:read`. Once created, the website tells you what to do.

Previously, this project was developed in [PHP](https://www.php.net/) 🐘 (available through the `no-svelte` branch), because the GmodStore API restricted [CORS headers](https://developer.mozilla.org/fr/docs/Web/HTTP/Guides/CORS), preventing direct communication from a browser. This restriction has since been removed, allowing usage of [Svelte](https://svelte.dev/) 🔥 framework to communicate **directly** with their API. This migration has eliminated need for an intermediary server, improving data privacy by running the **entire** website client-side, while optimizing its performance.

> [!NOTE]
> All or part of the code may contain comments in my native language (French) to ease development. 🌐

### Setup

> [!WARNING]
> Deployment in a production environment requires a pre-configured web server such as [Nginx](https://nginx.org/en/), [Apache](https://httpd.apache.org/), or [Caddy](https://caddyserver.com/) to serve the static files generated by Vite. ⚠️

#### Local development

- Install [NodeJS LTS](https://nodejs.org/) (>20 or higher) ;
- Install project dependencies using `npm install` ;
- Start Vite local server using `npm run dev`.

#### Production deployment

- Install [NodeJS LTS](https://nodejs.org/) (>20 or higher) ;
- Install project dependencies using `npm install` ;
- Build static website files using `npm run build` ;
- Remove development dependencies using `npm prune --omit=dev` ;
- Use a web server to serve the static files generated in the previous step.

*This website is in no way affiliated with GmodStore, except that I use their amazing API to provide you this service.*

![image](.gitlab/images/gmodstore-downloader.png)