<script lang="ts">
    import { asset } from "$app/paths";
    import { onMount } from "svelte";
    import { PUBLIC_VERSION,
        PUBLIC_ANALYTICS_ENABLED,
        PUBLIC_ANALYTICS_ENDPOINT,
        PUBLIC_ANALYTICS_PROJECT_ID,
        PUBLIC_ANALYTICS_RESPECT_DNT,
        PUBLIC_ANALYTICS_ENVIRONMENT } from "$app/env/public";

    let { children } = $props();
    let canTrack = $state( false );

    /**
     * Tells whether the analytics scripts may load for this visitor.
     *
     * The recorder has no privacy option and the tracker ignores Global Privacy
     * Control, so honouring Do Not Track or GPC means never loading them. Runs
     * in `onMount` since the prerender cannot read the browser's preference.
     * The cast covers GPC, missing from TypeScript's DOM library.
     *
     * @returns `true` when the visitor sends no opt-out signal that applies.
     * @author Claude
     */
    const isTrackingAllowed = (): boolean =>
    {
        const privacyNavigator = navigator as Navigator & { globalPrivacyControl?: boolean };
        const respectOptOut = PUBLIC_ANALYTICS_RESPECT_DNT === "true";
        const dntRequested = [ "1", "yes" ].includes( privacyNavigator.doNotTrack ?? "" );
        const gpcRequested = privacyNavigator.globalPrivacyControl === true;
        const optedOut = dntRequested || gpcRequested;

        return !( respectOptOut && optedOut );
    };

    onMount( () =>
    {
        canTrack = isTrackingAllowed();
    } );
</script>

<svelte:head>
    <link rel="icon" href={asset( "assets/favicons/16x16.webp" )} type="image/webp" sizes="16x16" />
    <link rel="icon" href={asset( "assets/favicons/32x32.webp" )} type="image/webp" sizes="32x32" />
    <link rel="icon" href={asset( "assets/favicons/48x48.webp" )} type="image/webp" sizes="48x48" />
    <link rel="icon" href={asset( "assets/favicons/192x192.webp" )} type="image/webp" sizes="192x192" />
    <link rel="icon" href={asset( "assets/favicons/512x512.webp" )} type="image/webp" sizes="512x512" />
    <link rel="apple-touch-icon" href={asset( "assets/favicons/180x180.webp" )} type="image/webp" sizes="180x180" />
    <link rel="manifest" href={asset( "manifest.webmanifest" )} crossorigin="use-credentials" />

    {#if PUBLIC_ANALYTICS_ENABLED === "true" && canTrack}
        <script
            src={PUBLIC_ANALYTICS_ENDPOINT + "/script.js"}
            defer
            data-errors="true"
            data-release={PUBLIC_VERSION || undefined}
            data-website-id={PUBLIC_ANALYTICS_PROJECT_ID}
            data-performance="true"
            data-environment={PUBLIC_ANALYTICS_ENVIRONMENT}
            data-do-not-track={PUBLIC_ANALYTICS_RESPECT_DNT}
            data-exclude-hash="true"
            data-exclude-search="true"
        ></script>

        <script
            src={PUBLIC_ANALYTICS_ENDPOINT + "/recorder.js"}
            defer
            data-website-id={PUBLIC_ANALYTICS_PROJECT_ID}
        ></script>
    {/if}
</svelte:head>

{@render children()}
