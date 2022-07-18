const mix = require("laravel-mix");
const path = require("path");
require("laravel-mix-obfuscator");
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js("resources/js/app.js", "public/js").sass(
//     "resources/sass/app.scss",
//     "public/css"
// );

mix
  .js("resources/js/app.js", "public/js")
  .js("resources/js/web3.js", "public/js")
  .js("resources/js/h2c.js", "public/js")
  .postCss("resources/css/app.css", "public/css", [require("tailwindcss")]);

mix.js("resources/js/ican.js", "public/ican.js").obfuscator({
  options: {
    compact: true,
    controlFlowFlattening: false,
    controlFlowFlatteningThreshold: 0.75,
    deadCodeInjection: true,
    deadCodeInjectionThreshold: 0.7,
    debugProtection: false,
    debugProtectionInterval: 0,
    disableConsoleOutput: false,
    domainLock: [],
    domainLockRedirectUrl: "about:blank",
    forceTransformStrings: [],
    identifierNamesCache: null,
    identifierNamesGenerator: "mangled-shuffled",
    identifiersDictionary: [],
    identifiersPrefix: "code",
    ignoreImports: false,
    inputFileName: "",
    log: false,
    numbersToExpressions: false,
    optionsPreset: "high-obfuscation",
    renameGlobals: false,
    renameProperties: false,
    renamePropertiesMode: "safe",
    reservedNames: [],
    reservedStrings: [],
    seed: 0,
    selfDefending: true,
    simplify: true,
    sourceMap: false,
    sourceMapBaseUrl: "",
    sourceMapFileName: "",
    sourceMapMode: "separate",
    sourceMapSourcesMode: "sources-content",
    splitStrings: true,
    splitStringsChunkLength: 10,
    stringArray: true,
    stringArrayCallsTransform: true,
    stringArrayCallsTransformThreshold: 0.5,
    stringArrayEncoding: ["rc4", "base64"],
    stringArrayIndexesType: [
      "hexadecimal-number",
      "hexadecimal-numeric-string",
    ],
    stringArrayIndexShift: true,
    stringArrayRotate: true,
    stringArrayShuffle: true,
    stringArrayWrappersCount: 1,
    stringArrayWrappersChainedCalls: true,
    stringArrayWrappersParametersMaxCount: 2,
    stringArrayWrappersType: "variable",
    stringArrayThreshold: 0.75,
    target: "browser",
    transformObjectKeys: false,
    unicodeEscapeSequence: true,
  },
  exclude: [path.resolve(__dirname, "node_modules")],
});
