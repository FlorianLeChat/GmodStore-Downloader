# 📥 GmodStore Downloader

## In French

Ce petit site Internet permet le téléchargement d'addons en provenance du [GmodStore](https://www.gmodstore.com/) sans passer le site en ligne mais grâce à son [API](https://docs.gmodstore.com/). Cela est très utile dans le cas où un propriétaire d'un compte souhaite donner la possibilité à des personnes tierces de télécharger ses addons créés/achetés sans leur donner des identifiants de connexion, c'est un moyen équivalent aux solutions des « **accès secondaires** ». Le propriétaire du compte doit seulement générer un jeton d'authentification avec certaines permissions restreintes afin de le donner aux personnes autorisées.

Les jetons peuvent être générés à cette adresse : https://www.gmodstore.com/settings/personal-access-tokens. Ils doivent comporter les autorisations suivantes : `products:read`, `product-versions:read`, `product-versions:download`, `users:read` et `user-purchases:read`. Une fois créés, le site vous indique la démarche à suivre.

<ins>Voici les exigences pour exécuter le site Internet</ins> :
* HTML **5** / CSS **3**
* PHP **8.1+**
* JavaScript (*ES6*)

*Ce site Internet n'est en aucun cas affilié à GmodStore, à l'exception du fait que nous utilisons leur formidable API pour vous fournir ce service.*

___

## In English

This simple website provides the possibility to download addons from the [GmodStore](https://www.gmodstore.com/) without going through the online website but using its [API](https://docs.gmodstore.com/). This is very useful in case an account owner wants to give the access to third parties to download his created/purchased addons without giving them login credentials, it is a equivalent to the "**secondary access**" way. The account owner only needs to generate an authentication token with some restricted permissions in order to give it to authorized persons.

Tokens can be generated at this address: https://www.gmodstore.com/settings/personal-access-tokens. They must have the following permissions: `products:read`, `product-versions:read`, `product-versions:download`, `users:read` and `user-purchases:read`. Once created, the site tells you what to do.

<ins>Here are the requirements to run the website</ins>:
* HTML **5** / CSS **3**
* PHP **8.1+**
* JavaScript (*ES6*)

*This website is in no way affiliated with GmodStore, except that we use their amazing API to provide you this service.*

![image](https://user-images.githubusercontent.com/26360935/190854337-559ea766-dc34-4b49-b9bb-f3f69399f92d.png)