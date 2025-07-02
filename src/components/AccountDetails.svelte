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
<h3>💰 120 EUR</h3>