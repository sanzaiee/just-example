import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/sass/app.scss",
                "resources/js/app.js",
                "resources/sass/backend-optimized.scss",
                "resources/js/backend-optimized.js",
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separate vendor chunks for better caching
                    vendor: ["jquery", "bootstrap"],
                    // TinyMCE in its own chunk since it's large
                    editor: ["tinymce"],
                    // DataTables in its own chunk
                    tables: ["datatables.net-bs5"],
                },
            },
        },
        // Enable source maps for debugging
        sourcemap: process.env.NODE_ENV === "development",
        // Optimize for production
        minify: "terser",
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.log in production
                drop_debugger: true,
            },
        },
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ["jquery", "bootstrap"],
    },
});
