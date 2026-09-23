import { defineConfig } from 'vite'
import { createRequire } from 'node:module'
import { fileURLToPath } from 'node:url'
import { cp, mkdir } from 'node:fs/promises'
import path from 'node:path'

const require = createRequire(import.meta.url)
const __dirname = path.dirname(fileURLToPath(import.meta.url))

// Local Gutenberg monorepo. Resolved through an alias rather than a symlink in
// node_modules, because npm treats such a symlink as an extraneous package and
// prunes the monorepo's own dependencies through it on the next install.
const gutenbergRoot = path.resolve(__dirname, 'wp_builds/wp_gutenberg')

// Destinations the built assets are copied into, see config.default.js.
const { paths: copyPaths = [] } = require('./config.js')

const copyToProjects = () => ({
    name: 'copy-to-projects',
    apply: 'build',
    async closeBundle () {
        // GUTENBERG_SKIP_COPY=1 builds without touching the projects in config.js.
        if (process.env.GUTENBERG_SKIP_COPY) {
            return
        }

        for (const dest of copyPaths) {
            for (const dir of ['js', 'css']) {
                await mkdir(path.join(dest, dir), { recursive: true })
                await cp(path.resolve(__dirname, 'public', dir), path.join(dest, dir), { recursive: true })
            }
        }
    },
})

export default defineConfig(({ mode }) => ({
    // The build writes into public/, so it must not also be treated as a static root.
    publicDir: false,
    define: {
        'process.env.NODE_ENV': JSON.stringify(mode === 'development' ? 'development' : 'production'),
        global: 'globalThis',
    },
    resolve: {
        // Webpack's field order. Vite would also read jsnext:main, which resolves moment
        // to its esm source; moment-timezone then gets a namespace instead of the
        // moment function and fails on moment.version.
        mainFields: ['browser', 'module', 'main'],
        alias: [
            // Stylesheets still use webpack's tilde prefix, which vite does not know.
            // The monorepo is mapped in a single step, postcss-import applies an alias only once.
            { find: /^~?gutenberg\//, replacement: gutenbergRoot + '/' },
            { find: /^~/, replacement: '' },
            { find: /^react$/, replacement: path.resolve(__dirname, 'src/resources/js/shims/react.js') },
            { find: /^react-dom$/, replacement: path.resolve(__dirname, 'src/resources/js/shims/react-dom.js') },
        ],
    },
    // JSX is compiled against the global React the shims read off window.
    oxc: {
        jsx: { runtime: 'classic', pragma: 'React.createElement', pragmaFrag: 'React.Fragment' },
    },
    plugins: [copyToProjects()],
    build: {
        outDir: 'public',
        emptyOutDir: false,
        sourcemap: true,
        minify: mode !== 'development',
        lib: {
            entry: path.resolve(__dirname, 'src/resources/js/gutenberg.js'),
            formats: ['iife'],
            name: 'Gutenberg',
            fileName: () => 'js/gutenberg.js',
        },
        rollupOptions: {
            output: {
                intro: [
                    // Gutenberg reads process.env.GUTENBERG_PHASE and FORCE_REDUCED_MOTION
                    // without a typeof guard. Webpack mocked process automatically.
                    'var process = { env: {}, browser: true, platform: "browser", version: "" };',
                    // The admin loads this bundle twice per page. A second evaluation would
                    // install a fresh module graph over window.wp and window.Gutenberg, so the
                    // editor element and the api fetch handler would belong to different copies
                    // and the post content would never load.
                    'if (window.__gutenbergBundle) { return window.__gutenbergBundle }',
                ].join('\n'),
                assetFileNames: (asset) =>
                    asset.names?.[0]?.endsWith('.css') ? 'css/gutenberg.css' : 'css/fonts/[name][extname]',
            },
        },
    },
}))
