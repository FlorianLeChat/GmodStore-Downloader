<script lang="ts">
    import type { UserProperties } from "../interfaces/UserProperties";
    import type { ProductProperties } from "../interfaces/ProductProperties";
    import DownloadButton from "./DownloadButton.svelte";

    let {
        token,
        userData,
        products
    }: {
        token: string;
        userData: UserProperties;
        products: ProductProperties[];
    } = $props();

    const calculateTotal = () =>
    {
        return products.reduce( ( previous, value ) =>
        {
            let currentPrice = previous;

            if ( value.price.raw !== 99999 )
            {
                currentPrice += parseInt( value.price.original.amount, 10 );
            }

            return currentPrice;
        }, 0 );
    };

    const getCurrency = () =>
    {
        const product = products.find( ( product ) => product.price.raw !== 99999 );

        return product ? product.price.original.currency : "EUR";
    };

    const formatMoney = ( amount: number ) =>
    {
        return Intl.NumberFormat( navigator.language, {
            style: "currency",
            currency: getCurrency(),
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        } ).format( amount / 100 );
    };
</script>

<h2>🔐 {userData.name} ({userData.slug}) [{userData.id}]</h2>

<ul>
    {#each products as product ( product.id )}
        <li>
            <b>{product.name}</b>
            <br />
            <DownloadButton {token} {product} />
            —
            <a
                rel="noopener noreferrer"
                href={"https://www.gmodstore.com/market/view/" + product.id}
                target="_blank"
            >
                Store
            </a>
        </li>
    {/each}
</ul>

<h3>💰 {formatMoney( calculateTotal() )} ({getCurrency()})</h3>