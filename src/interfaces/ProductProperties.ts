//
// Interface des propriétés pour les produits sur GmodStore.
//  Source : https://docs.pivity.com/#tag/Products/operation/getProduct
//
export interface ProductProperties
{
	// Identifiant unique du produit.
	id: string;
	productId: string;

	// Nom du produit.
	name: string;
}