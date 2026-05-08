<script lang="ts">
    import { env } from "$env/dynamic/public";
    import { onMount } from "svelte";
    import GitHubCorner from "./(components)/GitHubCorner.svelte";
    import type { User } from "$lib/types/user";
    import AccountDetails from "./(components)/AccountDetails.svelte";
    import type { Product } from "$lib/types/product";
    import AuthenticationForm from "./(components)/AuthenticationForm.svelte";
    import { downloadProduct } from "$lib/utilities/download";
    import { fetchUserData, fetchAllProducts, fetchAllPurchases } from "$lib/utilities/gmodstore";

    let token = $state( "" );
    let userData: User | undefined = $state();
    let products: Product[] = $state( [] );
    let exception = $state( "" );
    let isLoading = $state( false );

    const fetchAccountData = async () =>
    {
        isLoading = true;
        userData = await fetchUserData( token );
        products = await fetchAllProducts( token, await fetchAllPurchases( token, userData.id ) );
    };

    onMount( async () =>
    {
        const parameters = new URLSearchParams( window.location.search );
        const productId = parameters.get( "download" );

        token = parameters.get( "token" ) ?? "";

        if ( !token )
        {
            return;
        }

        try
        {
            if ( productId )
            {
                await downloadProduct( token, productId );
            }
            else
            {
                await fetchAccountData();
            }
        }
        catch ( error: unknown )
        {
            if ( error instanceof Error )
            {
                exception = error.message;
            }
            else
            {
                exception = "An unknown error occurred.";
            }
        }
        finally
        {
            isLoading = false;
        }
    } );
</script>

<main>
    <GitHubCorner />

    <h1>📥 GmodStore Downloader</h1>

    <code>Version { env.PUBLIC_VERSION ?? "0.0.1" }</code>

    {#if isLoading}
        <i>Please wait, fetching data...</i>
    {:else if token && userData && products}
        <AccountDetails {token} {userData} {products} />
    {:else}
        <AuthenticationForm />
    {/if}

    {#if exception}
        <h3>⚠️ Error output ⚠️</h3>

        <output>{exception}</output>
    {/if}
</main>
