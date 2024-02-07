# 📥 GmodStore Downloader

## In French

Ce petit site Internet permet le téléchargement d'addons en provenance du [GmodStore](https://www.gmodstore.com/) sans passer le site en ligne mais grâce à son [API](https://docs.gmodstore.com/). Cela est très utile dans le cas où un propriétaire d'un compte souhaite donner la possibilité à des personnes tierces de télécharger ses addons créés/achetés sans leur donner des identifiants de connexion, c'est un moyen équivalent aux solutions des « **accès secondaires** ». Le propriétaire du compte doit seulement générer un jeton d'authentification avec certaines permissions restreintes afin de le donner aux personnes autorisées.

Les jetons peuvent être générés à cette adresse : https://www.gmodstore.com/settings/personal-access-tokens. Ils doivent comporter les autorisations suivantes : `products:read`, `product-versions:read`, `product-versions:download`, `users:read` et `user-purchases:read`. Une fois créés, le site vous indique la démarche à suivre.

> [!NOTE]
> Voici les exigences pour exécuter le site Internet :
> * [**Toute** version de PHP maintenue](https://www.php.net/supported-versions.php)

> [!TIP]
> Pour essayer le projet, il suffit d'installer l'ensemble des dépendances nécessaires avec la commande `composer install` (nécessite [Composer](https://getcomposer.org/download/)) puis de lancer un serveur local HTTP utilisant PHP comme [WAMP](https://www.wampserver.com/) (Windows) ou [XAMPP](https://www.apachefriends.org/index.html) (Linux/MacOS). Une image Docker est aussi disponible pour tester ce projet pour les personnes les plus expérimentées ! 🐳

> [!WARNING]
> Ce projet utilise le [GmodStore SDK pour PHP](https://github.com/everyday-as/gmodstore-php-sdk) afin d'interagir plus facilement avec l'API du site Internet. Cependant, ce SDK est uniquement applicable pour la **deuxième** version de l'API et non pas pour la **troisième**, même si tout fonctionne correctement, il est nécessaire de faire une modification *assez bête* dans les fichiers pour que le téléchargement des addons fonctionnent.
>
> * Installez les dépendances de Composer nécessaires avec `composer install`.
> * Rendez-vous dans le fichier `/vendor/everyday/gmodstore-sdk/lib/Api/ProductVersionsApi.php` à la ligne 1005.
> * Remplacez la ligne contenant le code suivant :
> ```php
> ObjectSerializer::deserialize($content, '\Everyday\GmodStore\Sdk\Model\DownloadProductVersionResponse', []),
> ```
> par
> ```php
> json_decode($content, true),
> ```
> * Enregistrez le fichier et c'est tout !

*Ce site Internet n'est en aucun cas affilié à GmodStore, à l'exception du fait que j'utilise leur formidable API pour vous fournir ce service.*

___

## In English

This simple website provides the possibility to download addons from the [GmodStore](https://www.gmodstore.com/) without going through the online website but using its [API](https://docs.gmodstore.com/). This is very useful in case an account owner wants to give the access to third parties to download his created/purchased addons without giving them login credentials, it is a equivalent to the "**secondary access**" way. The account owner only needs to generate an authentication token with some restricted permissions in order to give it to authorized persons.

Tokens can be generated at this address: https://www.gmodstore.com/settings/personal-access-tokens. They must have the following permissions: `products:read`, `product-versions:read`, `product-versions:download`, `users:read` and `user-purchases:read`. Once created, the site tells you what to do.

> [!NOTE]
> Here are the requirements to run the website:
> * [**Any** maintained PHP versions](https://www.php.net/supported-versions.php)

> [!TIP]
> To test the project, you simply have to install all the necessary dependencies with `composer install` command (requires [Composer](https://getcomposer.org/download/)) and then launch a local HTTP server running PHP such as [WAMP](https://www.wampserver.com/) (Windows) or [XAMPP](https://www.apachefriends.org/index.html) (Linux/MacOS). A Docker image is also available to test this project for more experienced people! 🐳

> [!WARNING]
> This project uses the [GmodStore SDK for PHP](https://github.com/everyday-as/gmodstore-php-sdk) to interact more easily with the website API. However, this SDK is only applicable for the **second** version of the API and not for the **third** one, even if everything works correctly, it is necessary to make a *pretty stupid* change in the files to make the addons download work.
>
> * Install the necessary Composer dependencies with `composer install`.
> * Go to `/vendor/everyday/gmodstore-sdk/lib/Api/ProductVersionsApi.php` at line 1005.
> * Replace the line containing the following code:
> ```php
> ObjectSerializer::deserialize($content, '\Everyday\GmodStore\Sdk\Model\DownloadProductVersionResponse', []),
> ```
> par
> ```php
> json_decode($content, true),
> ```
> * Save the file and that's it!

*This website is in no way affiliated with GmodStore, except that I use their amazing API to provide you this service.*

![image](https://user-images.githubusercontent.com/26360935/190854337-559ea766-dc34-4b49-b9bb-f3f69399f92d.png)