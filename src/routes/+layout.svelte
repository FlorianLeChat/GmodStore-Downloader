<script lang="ts">
    import { env } from "$env/dynamic/public";
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

    {#if env.PUBLIC_ANALYTICS_ENABLED === "true"}
        <script
            src={env.PUBLIC_ANALYTICS_ENDPOINT + "/script.js"}
            defer
            data-website-id={env.PUBLIC_ANALYTICS_PROJECT_ID}
            data-performance="true"
            data-do-not-track={env.PUBLIC_ANALYTICS_RESPECT_DNT}
            data-exclude-hash="true"
            data-exclude-search="true"
        ></script>

        <script
            src={env.PUBLIC_ANALYTICS_ENDPOINT + "/recorder.js"}
            defer
            data-website-id={env.PUBLIC_ANALYTICS_PROJECT_ID}
        ></script>
    {/if}
</svelte:head>

{@render children()}
