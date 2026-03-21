import { svelte } from "@sveltejs/vite-plugin-svelte";
import { defineConfig } from "vite";

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