import { sveltekit } from "@sveltejs/kit/vite";
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
    plugins: [ sveltekit() ]
} );
