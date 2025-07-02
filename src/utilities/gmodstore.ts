import type { UserProperties } from "../interfaces/UserProperties";
import type { ProductProperties } from "../interfaces/ProductProperties";

//
// Récupère les informations d'un compte utilisateur GmodStore.
//  Source : https://docs.pivity.com/#tag/Users/operation/getMe
//
export const fetchUserData = async ( token: string ) =>
{
	const response = await fetch( "https://api.pivity.com/v3/me", {
		headers: {
			"X-Tenant": "gmodstore.com",
			"Authorization": `Bearer ${ token }`
		}
	} );

	const json = await response.json();

	if ( !response.ok )
	{
		throw new Error( json.message ?? "Failed to fetch user data." );
	}

	return json.data.user as UserProperties;
};

//
// Récupère tous les produits achetés par l'utilisateur.
//  Source : https://docs.pivity.com/#tag/User-Product-Purchases/operation/listUserPurchases
//
export const fetchAllPurchases = async (
	token: string,
	userId: string,
	cursor?: string
) =>
{
	const response = await fetch(
		`https://api.pivity.com/v3/users/${ userId }/purchases?perPage=100&cursor=${ cursor }`,
		{
			headers: {
				"X-Tenant": "gmodstore.com",
				"Authorization": `Bearer ${ token }`
			}
		}
	);

	const json = await response.json();

	if ( !response.ok )
	{
		throw new Error( json.message ?? "Failed to fetch purchases." );
	}

	let purchases: string[] = json.data?.map(
		( purchase: ProductProperties ) => purchase.productId
	);

	if ( purchases && json.cursors?.next )
	{
		const nextPurchases = await fetchAllPurchases(
			token,
			userId,
			json.cursors.next
		);

		purchases = purchases.concat( nextPurchases );
	}

	return purchases;
};

//
// Récupère les informations de tous les produits achetés par l'utilisateur.
//  Source : https://docs.pivity.com/#tag/Products/operation/getProducts
//
export const fetchAllProducts = async ( token: string, purchases: string[] ) =>
{
	const parameters = new URLSearchParams();
	purchases.forEach( ( purchase ) => parameters.append( "ids[]", purchase ) );

	const response = await fetch(
		`https://api.pivity.com/v3/products/batch?${ parameters }`,
		{
			headers: {
				"X-Tenant": "gmodstore.com",
				"Authorization": `Bearer ${ token }`
			}
		}
	);

	const json = await response.json();

	if ( !response.ok )
	{
		throw new Error( json.message ?? "Failed to fetch products." );
	}

	let products: ProductProperties[] = json.data;

	if ( products && purchases.length > 100 )
	{
		// L'API ne permet pas de récupérer plus de 100 produits à la fois.
		const nextPurchases = purchases.splice( 0, 100 );
		const nextProducts = await fetchAllProducts( token, nextPurchases );

		products = products.concat( nextProducts );
	}

	return products;
};

//
// Récupère les informations de la dernière version d'un produit spécifique.
//  Source : https://docs.pivity.com/#tag/Product-Versions/operation/listProductVersions
//
export const fetchProductLatestVersion = async (
	token: string,
	productId: string
) =>
{
	const response = await fetch(
		`https://api.pivity.com/v3/products/${ productId }/versions`,
		{
			headers: {
				"X-Tenant": "gmodstore.com",
				"Authorization": `Bearer ${ token }`
			}
		}
	);

	const json = await response.json();

	if ( !response.ok )
	{
		throw new Error( json.message ?? "Failed to fetch latest version." );
	}

	return json.data[ 0 ].id as string;
};

//
// Télécharge un produit spécifique à partir de son identifiant et de sa version.
//  Source : https://docs.pivity.com/#tag/Product-Versions/operation/getProductDownloadUrl
//
export const fetchProductDownloadUrl = async (
	token: string,
	productId: string,
	versionId: string
) =>
{
	const response = await fetch(
		`https://api.pivity.com/v3/products/${ productId }/versions/${ versionId }/download`,
		{
			method: "POST",
			headers: {
				"X-Tenant": "gmodstore.com",
				"Authorization": `Bearer ${ token }`
			}
		}
	);

	const json = await response.json();

	if ( !response.ok )
	{
		throw new Error( json.message ?? "Failed to download product." );
	}

	return json.data.url as string;
};