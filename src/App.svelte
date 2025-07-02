<script lang="ts">
	// Importation des dépendances et composants.
	import { onMount } from "svelte";
	import GitHubCorner from "./components/GitHubCorner.svelte";
	import AccountDetails from "./components/AccountDetails.svelte";
	import { fetchUserData,
		fetchAllProducts,
		fetchAllPurchases } from "./utilities/gmodstore";
	import AuthenticationForm from "./components/AuthenticationForm.svelte";
	import { downloadProduct } from "./utilities/download";
	import type { UserProperties } from "./interfaces/UserProperties";
	import type { ProductProperties } from "./interfaces/ProductProperties";

	// Initialisation des variables.
	let token = $state( "" );
	let userData: UserProperties | undefined = $state();
	let products: ProductProperties[] = $state( [] );
	let exception = $state( "" );
	let isLoading = $state( false );

	// Récupération des données du compte utilisateur depuis l'API de GmodStore.
	const fetchAccountData = async () =>
	{
		isLoading = true;
		userData = await fetchUserData( token );
		products = await fetchAllProducts(
			token,
			await fetchAllPurchases( token, userData.id )
		);
	};

	// Opérations de récupération des données à l'ouverture de la page.
	onMount( async () =>
	{
		const parameters = new URLSearchParams( window.location.search );
		const productId = parameters.get( "download" );

		token = parameters.get( "token" ) ?? "";

		if ( !token )
		{
			return;
		}

		try
		{
			if ( productId )
			{
				await downloadProduct( token, productId );
			}
			else
			{
				await fetchAccountData();
			}
		}
		catch ( error: unknown )
		{
			if ( error instanceof Error )
			{
				exception = error.message;
			}
			else
			{
				exception = "An unknown error occurred.";
			}
		}
		finally
		{
			isLoading = false;
		}
	} );
</script>

<!-- Conteneur général -->
<main>
	<!-- Logo GitHub -->
	<GitHubCorner />

	<!-- Titre -->
	<h1>📥 GmodStore Downloader</h1>

	{#if isLoading}
		<!-- Affichage d'un message de chargement -->
		<i>Please wait, fetching data...</i>
	{:else if token && userData && products}
		<!-- Affichage des détails du compte utilisateur -->
		<AccountDetails {token} {userData} {products} />
	{:else}
		<!-- Formulaire d'authentification -->
		<AuthenticationForm />
	{/if}

	{#if exception}
		<!-- Affichage des erreurs -->
		<h3>⚠️ Error output ⚠️</h3>

		<output>{exception}</output>
	{/if}
</main>