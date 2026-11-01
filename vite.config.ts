import adapter from "@sveltejs/adapter-static";
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
    plugins: [
        sveltekit( {
            adapter: adapter(),
            paths: {
                // @ts-expect-error Needed for GitLab Pages generation.
                base: process.argv.includes( "dev" ) ? "" : process.env.BASE_PATH
            }
        } )
    ]
} );
