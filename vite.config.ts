import { sveltekit } from "@sveltejs/kit/vite";
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
    plugins: [ sveltekit() ]
} );
