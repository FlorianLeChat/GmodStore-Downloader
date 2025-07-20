//
// Tests unitaires concernant les appels à l'API de GmodStore.
//
import { fetchUserData,
	fetchAllPurchases,
	fetchAllProducts,
	fetchProductDownloadUrl,
	fetchProductLatestVersion } from "../src/utilities/gmodstore";
import { describe, it, expect, vi, beforeEach, type Mock } from "vitest";

beforeEach( () =>
{
	vi.resetAllMocks();
	vi.stubGlobal( "fetch", vi.fn() );
} );

describe( "Récupération des informations du compte utilisateur", () =>
{
	it( "Doit retourner les données utilisateur si la requête réussit", async () =>
	{
		const mockUser = { id: "123" };

		( fetch as Mock ).mockResolvedValue( {
			ok: true,
			json: () => Promise.resolve( { data: { user: mockUser } } )
		} );

		const data = await fetchUserData( "test-token" );
		expect( data ).toEqual( mockUser );
		expect( fetch ).toHaveBeenCalledWith(
			"https://api.pivity.com/v3/me",
			expect.objectContaining( {
				headers: expect.objectContaining( {
					Authorization: "Bearer test-token"
				} )
			} )
		);
	} );

	it( "Doit lever une erreur si la requête échoue", async () =>
	{
		( fetch as Mock ).mockResolvedValue( {
			ok: false,
			json: () => Promise.resolve( { message: "Unauthorized" } )
		} );

		await expect( fetchUserData( "bad-token" ) ).rejects.toThrow(
			"Unauthorized"
		);
	} );
} );

describe( "Récupération des produits achetés par l'utilisateur", () =>
{
	it( "Doit retourner une liste de produits achetés si la requête réussit", async () =>
	{
		const mockPurchases = [ { productId: "prod1" }, { productId: "prod2" } ];

		( fetch as Mock ).mockResolvedValue( {
			ok: true,
			json: () =>
				Promise.resolve( {
					data: mockPurchases,
					cursors: { next: null }
				} )
		} );

		const purchases = await fetchAllPurchases( "token", "userId" );
		expect( purchases ).toEqual( [ "prod1", "prod2" ] );
	} );

	it( "Doit supporter la pagination pour récupérer tous les achats", async () =>
	{
		const firstPage = {
			ok: true,
			json: () =>
				Promise.resolve( {
					data: [ { productId: "prod1" } ],
					cursors: { next: "cursor2" }
				} )
		};

		const secondPage = {
			ok: true,
			json: () =>
				Promise.resolve( {
					data: [ { productId: "prod2" } ],
					cursors: { next: null }
				} )
		};

		( fetch as Mock )
			.mockResolvedValueOnce( firstPage )
			.mockResolvedValueOnce( secondPage );

		const purchases = await fetchAllPurchases( "token", "userId" );
		expect( purchases ).toEqual( [ "prod1", "prod2" ] );
		expect( fetch ).toHaveBeenCalledTimes( 2 );
	} );

	it( "Doit lever une erreur si la requête échoue", async () =>
	{
		( fetch as Mock ).mockResolvedValue( {
			ok: false,
			json: () =>
				Promise.resolve( { message: "Error fetching purchases" } )
		} );

		await expect( fetchAllPurchases( "token", "userId" ) ).rejects.toThrow(
			"Error fetching purchases"
		);
	} );
} );

describe( "Récupération de la liste des produits de l'utilisateur", () =>
{
	it( "Doit retourner les produits si la requête réussit", async () =>
	{
		const mockProducts = [ { id: "prod1" }, { id: "prod2" } ];
		( fetch as Mock ).mockResolvedValue( {
			ok: true,
			json: () => Promise.resolve( { data: mockProducts } )
		} );

		const products = await fetchAllProducts( "token", [ "prod1", "prod2" ] );
		expect( products ).toEqual( mockProducts );
	} );

	it( "Doit lever une erreur si la requête échoue", async () =>
	{
		( fetch as Mock ).mockResolvedValue( {
			ok: false,
			json: () => Promise.resolve( { message: "Error fetching products" } )
		} );

		await expect( fetchAllProducts( "token", [ "prod1" ] ) ).rejects.toThrow(
			"Error fetching products"
		);
	} );
} );

describe( "Récupération de la dernière version d'un produit", () =>
{
	it( "Doit retourner l'identifiant de la dernière version", async () =>
	{
		( fetch as Mock ).mockResolvedValue( {
			ok: true,
			json: () => Promise.resolve( { data: [ { id: "version1" } ] } )
		} );

		const versionId = await fetchProductLatestVersion( "token", "productId" );
		expect( versionId ).toBe( "version1" );
	} );

	it( "Doit lever une erreur si la requête échoue", async () =>
	{
		( fetch as Mock ).mockResolvedValue( {
			ok: false,
			json: () =>
				Promise.resolve( { message: "Error fetching latest version" } )
		} );

		await expect(
			fetchProductLatestVersion( "token", "productId" )
		).rejects.toThrow( "Error fetching latest version" );
	} );
} );

describe( "Récupération du lien de téléchargement d'un produit", () =>
{
	it( "Doit retourner le lien de téléchargement si la requête réussit", async () =>
	{
		( fetch as Mock ).mockResolvedValue( {
			ok: true,
			json: () =>
				Promise.resolve( {
					data: { url: "https://download.url/file.zip" }
				} )
		} );

		const url = await fetchProductDownloadUrl(
			"token",
			"productId",
			"versionId"
		);
		expect( url ).toBe( "https://download.url/file.zip" );
	} );

	it( "Doit lever une erreur si la requête échoue", async () =>
	{
		( fetch as Mock ).mockResolvedValue( {
			ok: false,
			json: () =>
				Promise.resolve( { message: "Error downloading product" } )
		} );

		await expect(
			fetchProductDownloadUrl( "token", "productId", "versionId" )
		).rejects.toThrow( "Error downloading product" );
	} );
} );