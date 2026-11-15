import { defineEnvVars } from "@sveltejs/kit/env";

export const variables = defineEnvVars( {
    PUBLIC_VERSION: {
        public: true,
        static: true,
        description: "Application version displayed below the title (set by the CI)",
        schema: ( value ) => value || "0.0.1"
    },
    PUBLIC_ANALYTICS_ENABLED: {
        public: true,
        static: true,
        description: "Whether the analytics scripts are injected (\"true\" to enable)"
    },
    PUBLIC_ANALYTICS_ENDPOINT: {
        public: true,
        static: true,
        description: "Base URL of the analytics server"
    },
    PUBLIC_ANALYTICS_PROJECT_ID: {
        public: true,
        static: true,
        description: "Website identifier on the analytics server"
    },
    PUBLIC_ANALYTICS_ENVIRONMENT: {
        public: true,
        static: true,
        description: "Environment name reported to Umami with each event",
        schema: ( value ) => value ?? ""
    },
    PUBLIC_ANALYTICS_RESPECT_DNT: {
        public: true,
        static: true,
        description: "Whether analytics honour the visitor's Do Not Track and Global Privacy Control signals"
    }
} );
