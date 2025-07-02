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

	// Informations monétaires du produit.
	price: {
		// Prix brut du produit.
		raw: number;

		// Prix original du produit.
		original: {
			// Montant du prix original.
			amount: string;

			// Devise du prix original.
			currency: string;
		};
	};
}