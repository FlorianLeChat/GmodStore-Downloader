import { fetchProductDownloadUrl,
    fetchProductLatestVersion } from "./gmodstore";

export const downloadProduct = async ( token: string, productId: string ) =>
{
    const versionId = await fetchProductLatestVersion( token, productId );

    globalThis.location.href = await fetchProductDownloadUrl(
        token,
        productId,
        versionId
    );
};