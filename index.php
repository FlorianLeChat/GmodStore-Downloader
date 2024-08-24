<?php
	// Download an addon using its UUID.
	$token = htmlspecialchars($_GET["token"] ?? "", ENT_QUOTES, "UTF-8");
	$downloadId = htmlspecialchars($_GET["download"] ?? "", ENT_QUOTES, "UTF-8");

	if (!empty($downloadId))
	{
		$versionId = "";
		$downloadUrl = "";

		// Get the latest version of the product.
		function getLatestProductVersion(): void
		{
			global $token, $output, $versionId, $downloadId;

			$productVersion = file_get_contents("https://api.pivity.com/v3/products/$downloadId/versions", context: stream_context_create([
				"http" => [
					"header" => "Authorization: Bearer " . $token . "\r\nX-Tenant: gmodstore.com\r\n",
					"ignore_errors" => true
				]
			]));

			$productVersion = json_decode($productVersion, true);

			if (empty($productVersion["data"]))
			{
				$output .= $productVersion["message"] . "<br />" . PHP_EOL;
			}
			else
			{
				$versionId = $productVersion["data"][0]["id"];
			}
		}

		// Generate a download token for the product.
		function getProductDownloadToken(): void
		{
			global $token, $output, $downloadId, $versionId, $downloadUrl;

			$productDownload = file_get_contents("https://api.pivity.com/v3/products/$downloadId/versions/$versionId/download", context: stream_context_create([
				"http" => [
					"method" => "POST",
					"header" => "Authorization: Bearer " . $token . "\r\nX-Tenant: gmodstore.com\r\n",
					"ignore_errors" => true
				]
			]));

			$productDownload = json_decode($productDownload, true);

			if (empty($productDownload["data"]))
			{
				$output .= $productDownload["message"] . "<br />" . PHP_EOL;
			}
			else
			{
				$downloadUrl = $productDownload["data"]["url"];
			}
		}

		getLatestProductVersion();
		getProductDownloadToken();

		if (!empty($versionId) && !empty($downloadUrl) && filter_var($downloadUrl, FILTER_VALIDATE_URL))
		{
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Location: " . $downloadUrl);
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);

			exit();
		}
	}

	// Display all purchased addons.
	if (!empty($token))
	{
		// Login to user account.
		$output = "";
		$userId = "";
		$userData = file_get_contents("https://api.pivity.com/v3/me", context: stream_context_create([
			"http" => [
				"header" => "Authorization: Bearer " . $token . "\r\nX-Tenant: gmodstore.com\r\n",
				"ignore_errors" => true
			]
		]));

		$userData = json_decode($userData, true);

		if (empty($userData["data"]["user"]))
		{
			// Authentication failed.
			$output .= $userData["message"] . "<br />" . PHP_EOL;
		}
		else
		{
			// Authentication successful.
			$userData = $userData["data"]["user"];

			$userId = $userData["id"];
			$account = $userData["name"] . " (" . $userData["steamId"] . ") [" . $userId . "]";
		}

		// Retrieving purchased scripts.
		$productIdentifiers = [];

		function getProductIdentifiers(?string $cursor): void
		{
			// Make a first API call to get the first page of purchases.
			global $userId, $token, $output, $productIdentifiers;

			$userPurchases = file_get_contents("https://api.pivity.com/v3/users/$userId/purchases?perPage=100&cursor=$cursor", context: stream_context_create([
				"http" => [
					"header" => "Authorization: Bearer " . $token . "\r\nX-Tenant: gmodstore.com\r\n",
					"ignore_errors" => true
				]
			]));

			$userPurchases = json_decode($userPurchases, true);

			if (empty($userPurchases["data"]))
			{
				$output .= $userPurchases["message"] . "<br />" . PHP_EOL;
			}
			else
			{
				$productIdentifiers = array_merge($productIdentifiers, array_column($userPurchases["data"], "productId"));
			}

			// Checking all pages given by the API.
			$cursor = $userPurchases["cursors"]["next"];

			if (!empty($cursor))
			{
				getProductIdentifiers($cursor);
			}
		}

		getProductIdentifiers(null);

		// Retrieving data from previously collected scripts.
		$products = [];

		function getProductDetails(): void
		{
			// Make a first API call to get the first page of scripts.
			global $token, $output, $products, $productIdentifiers;

			$parameters = http_build_query(["ids" => $productIdentifiers]);
			$productDetails = file_get_contents("https://api.pivity.com/v3/products/batch?$parameters", context: stream_context_create([
				"http" => [
					"header" => "Authorization: Bearer " . $token . "\r\nX-Tenant: gmodstore.com\r\n",
					"ignore_errors" => true
				]
			]));

			$productDetails = json_decode($productDetails, true);

			if (empty($productDetails["data"]))
			{
				$output .= $productDetails["message"] . "<br />" . PHP_EOL;
			}
			else
			{
				$products = array_merge($products, $productDetails["data"]);
			}

			// Checking all identifiers fetched previously.
			// The API only allows 100 identifiers per request.
			if (count($productIdentifiers) > 100)
			{
				$productIdentifiers = array_slice($productIdentifiers, 100);

				getProductDetails();
			}
		}

		getProductDetails();

		// Calculating the total amount spent.
		$total = 0;
		$addons = "";

		foreach ($products as $product)
		{
			// Building the HTML structure.
			$addons .= '
				<li>
					<b>' . $product["name"] . '</b>
					<br />
					<a href="?token=' . $token . '&download=' . $product["id"] . '">Download</a>
					—
					<a href="https://www.gmodstore.com/market/view/' . $product["id"] . '" target="_blank">Store</a>
				</li>'
			;

			// Calculating the price of all addons.
			$currency = $product["price"]["original"]["currency"];

			if ($product["price"]["raw"] !== 99999)
			{
				$total += intval($product["price"]["original"]["amount"]);
			}
		}

		// Displaying the total money spent.
		$money = number_format($total / 100, 2, ",", " ") . " " . $currency;
	}
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<!-- Document metadata -->
		<meta charset="utf-8" />
		<meta name="author" content="Florian Trayon" />
		<meta name="description" content="A simple web page to download addons through the GmodStore API." />
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
		<a href="https://github.com/FlorianLeChat/GmodStore-Downloader" title="GitHub" target="_blank" aria-label="GitHub">
			<svg width="80" height="80" viewBox="0 0 250 250">
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

			<output><?= $output ?></output>
		<?php endif; ?>
	</body>
</html>