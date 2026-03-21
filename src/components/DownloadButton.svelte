<script lang="ts">
    import { downloadProduct } from "../utilities/download";
    import type { ProductProperties } from "../interfaces/ProductProperties";

    let {
        token,
        product
    }: {
        token: string;
        product: ProductProperties;
    } = $props();
</script>

<a
    href="?token={token}&download={product.id}"
    onclick={( event ) =>
    {
        event.preventDefault();

        downloadProduct( token, product.id );
    }}
>
    Download
</a>