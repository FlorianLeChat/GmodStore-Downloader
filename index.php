<!DOCTYPE html>

<?php
	require_once(__DIR__ . "/vendor/autoload.php");

	// Download an addon using its UUID.
	$token = $_GET["token"] ?? "";
	$download_id = $_GET["download"] ?? "";

	$config = \Everyday\GmodStore\Sdk\Configuration::getDefaultConfiguration()->setAccessToken($token);
	$client = new \GuzzleHttp\Client();

	if (!empty($download_id))
	{
		$download_product = new \Everyday\GmodStore\Sdk\Api\ProductVersionsApi($client, $config);

		try
		{
			$result = $download_product->listProductVersions($download_id, 1);
			$result = json_decode($result[0], true);
			$result = $result["data"][0];

			$version_id = $result["id"];
		}
		catch (Exception $error)
		{
			$output .= $error->getMessage() . "<br />" . PHP_EOL;
		}

		try
		{
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
			$error .= $error->getMessage();
		}
	}

	// Display all purchased addons.
	if (!empty($token))
	{
		// Login to user account.
		$output = "";
		$user_id = "";
		$user_data = new \Everyday\GmodStore\Sdk\Api\UsersApi($client, $config);

		try
		{
			$result = $user_data->getMe();
			$result = $result["data"]["user"];

			$user_id = $result["id"];
			$account = $result["name"] . " (" . $result["steamId"] . ") [" . $user_id . "]";
		}
		catch (Exception $error)
		{
			$output .= $error->getMessage() . "<br />" . PHP_EOL;
		}

		// Retrieving purchased scripts.
		$user_purchases = new \Everyday\GmodStore\Sdk\Api\UserProductPurchasesApi($client, $config);
		$product_identifiers = [];

		function getProductIdentifiers(string $id, string $cursor = null)
		{
			global $user_purchases;
			$result = $user_purchases->listUserPurchases($id, $cursor);
			$result = json_decode($result[0], true);

			global $product_identifiers;
			$product_identifiers = array_merge($product_identifiers, array_column($result["data"], "productId"));

			// Checking all pages returned by the API.
			$cursor = $result["cursors"]["next"];

			if (!empty($cursor))
			{
				getProductIdentifiers($id, $cursor);
			}
		}

		try
		{
			getProductIdentifiers($user_id);

			$product_identifiers["ids[]"] = $product_identifiers;
		}
		catch (Exception $error)
		{
			$output .= $error->getMessage() . "<br />" . PHP_EOL;
		}

		// Retrieving data from previously collected scripts.
		$product_informations = new \Everyday\GmodStore\Sdk\Api\ProductsApi($client, $config);

		try {
			$result = $product_informations->getProducts($product_identifiers);
			$result = $result["data"];

			$total = 0;
			$addons = "";

			foreach ($result as $value)
			{
				// Building the HTML structure.
				$addons .= '
					<li>
						<b>' . $value["name"] . '</b>
						<br />
						<a href="' . $_SERVER["PHP_SELF"] . '?token=' . $token . '&download=' . $value["id"] . '">Download</a>
						—
						<a href="https://www.gmodstore.com/market/view/' . $value["id"] . '" target="_blank">Store</a>
					</li>'
				;

				// Calculating the price of all addons.
				$currency = $value["price"]["original"]["currency"];

				if ($value["price"]["raw"] !== 99999)
				{
					$total += intval($value["price"]["original"]["amount"]);
				}
			}

			// Displaying the total money spent.
			$money = number_format($total / 100, 2, ",", " ") . " " . $currency;
		}
		catch (Exception $error)
		{
			$output .= $error->getMessage() . "<br />" . PHP_EOL;
		}
	}
?>

<html lang="en" dir="auto">
	<head>
		<!-- Document metadata -->
		<meta charset="utf-8" />
		<meta name="author" content="Florian Trayon" />
		<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />

		<!-- Document title -->
		<title>GmodStore Downloader</title>

		<!-- Document icons -->
		<link rel="icon" type="image/webp" sizes="16x16" href="assets/favicons/16x16.webp" />
		<link rel="icon" type="image/webp" sizes="32x32" href="assets/favicons/32x32.webp" />
		<link rel="icon" type="image/webp" sizes="48x48" href="assets/favicons/48x48.webp" />
		<link rel="icon" type="image/webp" sizes="192x192" href="assets/favicons/192x192.webp" />
		<link rel="icon" type="image/webp" sizes="512x512" href="assets/favicons/512x512.webp" />
		<link rel="apple-touch-icon" href="assets/favicons/180x180.webp" />

		<!-- CSS style rules -->
		<style>
			h1 a
			{
				/* Home page title */
				text-decoration: none;
			}

			input[type = text]
			{
				/* Home page input field */
				width: calc(100% - 0.5rem);
				display: block;
				max-width: 30rem;
				margin-bottom: 1rem;
			}
		</style>
	</head>
	<body>
		<!-- Title -->
		<h1><a href="https://github.com/FlorianLeChat/GmodStore-Downloader" target="_blank">📥</a> GmodStore Downloader</h1>

		<!-- Account details -->
		<?php if (!empty($account)):  ?>
			<h2>🔐 <?= $account ?></h2>
		<?php endif; ?>

		<?php if (!empty($addons)):  ?>
			<!-- Addons list -->
			<ul>
				<?= $addons ?>
			</ul>

			<!-- Money spent -->
			<h3>💰 <?= $money ?></h3>
		<?php else: ?>
			<!-- Authentication form -->
			<p>
				A token can be generated at the following address (<strong>account login required</strong>):
				<a href="https://www.gmodstore.com/settings/personal-access-tokens" target="_blank">https://www.gmodstore.com/settings/personal-access-tokens</a><br />

				The permissions to be granted when creating the token are:
				<code>products:read</code>, <code>product-versions:read</code>, <code>product-versions:download</code>, <code>users:read</code> et <code>user-purchases:read</code>.<br />

				Please note that the token will be displayed only once when it is created, so remember to save it somewhere safe!<br />

				Once retrieved, simply paste it into the field below. <strong>Do not share it with others unless you know what you are doing</strong>.
			</p>

			<form method="GET">
				<label for="token">Account authentication token :</label>
				<input type="text" autoComplete="off" spellCheck="false" id="token" name="token" required />

				<input type="submit" value="Connect" />
			</form>
		<?php endif; ?>

		<!-- Error output -->
		<?php if (!empty($output)):  ?>
			<h3>⚠️ Error output ⚠️</h3>

			<p><?= $output ?></p>
		<?php endif; ?>
	</body>
</html>