import type { UserProperties } from "../interfaces/UserProperties";
import type { ProductProperties } from "../interfaces/ProductProperties";

export const fetchUserData = async ( token: string ) =>
{
    // https://docs.pivity.com/#tag/Users/operation/getMe
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

export const fetchAllPurchases = async (
    token: string,
    userId: string,
    cursor?: string
) =>
{
    // https://docs.pivity.com/#tag/User-Product-Purchases/operation/listUserPurchases
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

export const fetchAllProducts = async ( token: string, purchases: string[] ) =>
{
    const parameters = new URLSearchParams();
    purchases.forEach( ( purchase ) => parameters.append( "ids[]", purchase ) );

    // https://docs.pivity.com/#tag/Products/operation/getProducts
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
        // The API does not allow fetching more than 100 products at once.
        const nextPurchases = purchases.splice( 0, 100 );
        const nextProducts = await fetchAllProducts( token, nextPurchases );

        products = products.concat( nextProducts );
    }

    return products;
};

export const fetchProductLatestVersion = async (
    token: string,
    productId: string
) =>
{
    // https://docs.pivity.com/#tag/Product-Versions/operation/listProductVersions
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

export const fetchProductDownloadUrl = async (
    token: string,
    productId: string,
    versionId: string
) =>
{
    // https://docs.pivity.com/#tag/Product-Versions/operation/getProductDownloadUrl
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