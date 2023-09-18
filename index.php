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
			header("Location: " . $result["data"]["url"]);
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

		try
		{
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
						<a href="?token=' . $token . '&download=' . $value["id"] . '">Download</a>
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

<!DOCTYPE html>

<html lang="en">
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

		<!-- CSS stylesheet -->
		<link rel="stylesheet" href="styles/styles.css" />
	</head>
	<body>
		<!-- Animated GitHub repository icon -->
		<!-- Source: https://tholman.com/github-corners/ -->
		<a href="https://github.com/FlorianLeChat/GmodStore-Downloader" target="_blank" style="position: fixed; inset: 0 0 auto auto; clip-path: polygon(0 0, 100% 0, 100% 100%);">
			<svg width="80" height="80" viewBox="0 0 250 250" style="fill: #151513; color: #fff">
				<path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path>
				<path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;"></path>
				<path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor"></path>
			</svg>
		</a>

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