<script lang="ts">
	// Importation des dépendances et composants.
	import type { UserProperties } from "../interfaces/UserProperties";
	import type { ProductProperties } from "../interfaces/ProductProperties";
	import DownloadButton from "./DownloadButton.svelte";

	// Initialisation des variables.
	let {
		token,
		userData,
		products
	}: {
		token: string;
		userData: UserProperties;
		products: ProductProperties[];
	} = $props();

	// Calcul du montant total dépensé par l'utilisateur pour les produits.
	const calculateTotal = () =>
	{
		return products.reduce( ( previous, value ) =>
		{
			let currentPrice = previous;

			if ( value.price.raw !== 99999 )
			{
				currentPrice += parseInt( value.price.original.amount, 10 );
			}

			return currentPrice;
		}, 0 );
	};

	// Récupération de la devise utilisée pour les prix des produits.
	const getCurrency = () =>
	{
		const product = products.find( ( product ) => product.price.raw !== 99999 );

		return product ? product.price.original.currency : "EUR";
	};

	// Mise en forme de l'argent pour l'affichage.
	const formatMoney = ( amount: number ) =>
	{
		return Intl.NumberFormat( navigator.language, {
			style: "currency",
			currency: getCurrency(),
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		} ).format( amount / 100 );
	};
</script>

<!-- Informations du compte utilisateur -->
<h2>🔐 {userData.name} ({userData.slug}) [{userData.id}]</h2>

<!-- Liste des produits -->
<ul>
	{#each products as product ( product.id )}
		<li>
			<b>{product.name}</b>
			<br />
			<DownloadButton {token} {product} />
			—
			<a
				rel="noopener noreferrer"
				href={"https://www.gmodstore.com/market/view/" + product.id}
				target="_blank"
			>
				Store
			</a>
		</li>
	{/each}
</ul>

<!-- Argent dépensé -->
<h3>💰 {formatMoney( calculateTotal() )} ({getCurrency()})</h3>