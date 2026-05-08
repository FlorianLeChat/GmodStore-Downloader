<script lang="ts">
    import { asset } from "$app/paths";

    let { children } = $props();
</script>

<svelte:head>
    <link rel="icon" href={asset( "/assets/favicons/16x16.webp" )} type="image/webp" sizes="16x16" />
    <link rel="icon" href={asset( "/assets/favicons/32x32.webp" )} type="image/webp" sizes="32x32" />
    <link rel="icon" href={asset( "/assets/favicons/48x48.webp" )} type="image/webp" sizes="48x48" />
    <link rel="icon" href={asset( "/assets/favicons/192x192.webp" )} type="image/webp" sizes="192x192" />
    <link rel="icon" href={asset( "/assets/favicons/512x512.webp" )} type="image/webp" sizes="512x512" />
    <link rel="apple-touch-icon" href={asset( "/assets/favicons/180x180.webp" )} type="image/webp" sizes="180x180" />
    <link rel="manifest" href={asset( "/manifest.webmanifest" )} crossorigin="use-credentials" />
</svelte:head>

{@render children()}
