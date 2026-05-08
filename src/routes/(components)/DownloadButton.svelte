<script lang="ts">
    import { downloadProduct } from "$lib/utilities/download";
    import type { Product } from "$lib/types/product";

    let { token, product }: { token: string; product: Product } = $props();
</script>

<a
    rel="external"
    href="?token={token}&download={product.id}"
    onclick={( event ) =>
    {
        event.preventDefault();

        downloadProduct( token, product.id );
    }}
>
    Download
</a>
