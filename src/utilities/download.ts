import { fetchProductDownloadUrl,
	fetchProductLatestVersion } from "./gmodstore";

//
// Télécharge un produit spécifique à partir de son identifiant et de sa version.
//
export const downloadProduct = async ( token: string, productId: string ) =>
{
	const versionId = await fetchProductLatestVersion( token, productId );
	const downloadUrl = await fetchProductDownloadUrl(
		token,
		productId,
		versionId
	);

	window.location.href = downloadUrl;
};