<?php
	//
	// Téléchargement à distance de scripts GmodStore.
	// Source : https://github.com/everyday-as/gmodstore-php-sdk
	//

	require_once(__DIR__ . "/vendor/autoload.php");

	// Définition du jeton d'authentification.
	$token = $_GET["token"];
	$config = \Everyday\GmodStore\Sdk\Configuration::getDefaultConfiguration()->setAccessToken($token);
	$client = new \GuzzleHttp\Client();

	// Vérification de la présence d'un identifiant en paramètres GET.
	$download_id = $_GET["download"] ?? "";

	if (!empty($download_id))
	{
		// Récupération de la dernière version du script demandé.
		$version_id = "";
		$download_product = new \Everyday\GmodStore\Sdk\Api\ProductVersionsApi($client, $config);

		try
		{
			// Appel de l'API.
			$result = $download_product->listProductVersions($download_id, 1);

			// Transformation des données JSON en tableau.
			$result = json_decode($result[0], true);

			// Récupération des données des versions.
			$result = $result["data"][0];

			// Récupération de l'identifiant unique de la dernière version.
			$version_id = $result["id"];
		}
		catch (Exception $error)
		{
			echo($error->getMessage()) . "<br />" . PHP_EOL;
		}

		// Téléchargement de la dernière version du script.
		try
		{
			// Récupération d'un lien de téléchargement unique.
			//	Note : une modification dans code source est a réaliser pour que la fonction
			//		retourne un résultat correcte, ligne 1005 de /vendor/everyday/gmodstore-sdk/lib/Api/ProductVersionsApi.php,
			//		il est modifier de modifier la ligne par « json_decode($content, true) »,
			//		la bibliothèque a été conçue pour la version 2 de l'API et mon script utilise
			//		la dernière et troisième version d'où cette modification nécessaire.
			$result = $download_product->getProductDownloadToken($download_id, $version_id);

			header("Pragma: no-cache");
			header("Expires: 0");
			header("Location: " . $result["url"]);
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);

			exit();
		}
		catch (Exception $error)
		{
			echo($error->getMessage()) . "<br />" . PHP_EOL;
		}
	}

	// Récupération de l'utilisateur lié au jeton d'authentification utilisé.
	$user_id = "";
	$user_data = new \Everyday\GmodStore\Sdk\Api\UsersApi($client, $config);

	try
	{
		// Appel de l'API.
		$result = $user_data->getMe();

		// Récupération des informations.
		$result = $result["data"]["user"];
		$user_id = $result["id"];

		// Affichage des informations.
		echo("<h1>Bienvenue " . $result["name"] . " (" . $result["steamId"] . ") [" . $user_id . "]</h1>");
	}
	catch (Exception $error)
	{
		echo($error->getMessage()) . "<br />" . PHP_EOL;
	}

	// Récupération des scripts achetés par l'utilisateur.
	$user_purchases = new \Everyday\GmodStore\Sdk\Api\UserProductPurchasesApi($client, $config);
	$product_identifiers = [];

	function getProductIdentifiers(string $id, string $cursor = null)
	{
		// Appel de l'API.
		global $user_purchases;
		$result = $user_purchases->listUserPurchases($id, $cursor);

		// Transformation des données JSON en tableau.
		$result = json_decode($result[0], true);

		// Récupération des identifiants uniques des scripts.
		global $product_identifiers;
		$product_identifiers = array_merge($product_identifiers, array_column($result["data"], "productId"));

		// Itération à travers les autres résultats.
		$cursor = $result["cursors"]["next"];

		if (!empty($cursor))
		{
			getProductIdentifiers($id, $cursor);
		}
	}

	try
	{
		// Exécution de la fonction récursive.
		getProductIdentifiers($user_id);

		// Assemblage des résultats.
		$product_identifiers["ids[]"] = $product_identifiers;
	}
	catch (Exception $error)
	{
		echo($error->getMessage()) . "<br />" . PHP_EOL;
	}


	// Récupération des informations des scripts récupérés.
	$product_informations = new \Everyday\GmodStore\Sdk\Api\ProductsApi($client, $config);

	try {
		// Appel de l'API.
		$result = $product_informations->getProducts($product_identifiers);

		// Récupération de la liste des scripts.
		$result = $result["data"];

		// Affichage de la liste des scripts achetés.
		$total = 0;

		echo("<h2>Liste des scripts achetés :</h2>");
		echo("<ul>");

		foreach ($result as $value)
		{
			// Construction du code HTML.
			echo('
				<li>
					<b>' . $value["name"] . '</b>
					<br />
					<a href="' . $_SERVER["PHP_SELF"] . '?token=' . $token . '&download=' . $value["id"] . '">Télécharger</a>
					—
					<a href="https://www.gmodstore.com/market/view/' . $value["id"] . '" target="_blank">Magasin</a>
				</li>'
			);

			// Calcul du prix du script.
			if ($value["price"]["raw"] !== 99999)
			{
				$total += intval($value["price"]["original"]["amount"]);
			}

			// Récupération de la devise.
			$currency = $value["price"]["original"]["currency"];
		}

		echo("</ul>");

		// Affichage de l'argent dépensé pour les scripts.
		echo("<h3>Argent total dépensé : " . number_format($total / 100, 2, ",", " ") . " " . $currency . " !</h3>");
	}
	catch (Exception $error)
	{
		echo($error->getMessage()) . "<br />" . PHP_EOL;
	}
?>