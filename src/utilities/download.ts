import { fetchProductDownloadUrl,
	fetchProductLatestVersion } from "./gmodstore";

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