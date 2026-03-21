import { svelte } from "@sveltejs/vite-plugin-svelte";
import { defineConfig } from "vitest/config";

export default defineConfig( {
    test: {
        projects: [
            {
                test: {
                    name: "gmodstore-downloader"
                },
                extends: "./vite.config.ts"
            }
        ]
    },
    plugins: [ svelte() ]
} );
