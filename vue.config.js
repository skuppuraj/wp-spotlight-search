const devPort = 8081;
const WebpackShellPluginNext = require("webpack-shell-plugin-next");
process.env.VUE_APP_VERSION = require("./package.json").version;
const productionDir = "./dist";
const outPath = `${productionDir}/wp-spotlight-search`;
const zipName = `wp-spotlight-search-${process.env.VUE_APP_VERSION}.zip`;
let production = [];
const isProduction = process.env.NODE_ENV === "production";

if (isProduction) {
  production.push(
    new WebpackShellPluginNext({
      onBuildStart: {
        scripts: ["rm -rf " + productionDir + " && mkdir -p " + productionDir],
        blocking: true,
        parallel: false,
      },
      onBuildEnd: {
        scripts: [
          "npx cpy --parents '.' '!./public' '!./dist/favicon.ico' '!./dist/index.html' !./src !./src/**/scss '!./config' '!./tests' '!./cypress' '!./**/node_modules' '!./**/__debugger1.php' '!./vue.config.js' '!./babel.config.js' '!./_dev_config.php' '!./webpack.config.js' '!./postcss.config.js' '!./package.json' '!./package-lock.json' '!./composer.json' '!./composer.lock' '!./cypress.json' '!./e2e' '!./jsconfig.json' " +
            outPath +
            " && cd " +
            productionDir +
            " && zip --recurse-paths " +
            zipName +
            " ./wp-spotlight-search",
        ],
        blocking: false,
        parallel: true,
      },
    })
  );
}
const fs = require('fs')
module.exports = {
  devServer: {
    hot: true,
    liveReload: false,
    headers: { "Access-Control-Allow-Origin": "*" },
    port: devPort,
    https: {
      key: fs.readFileSync('/Users/apple/Downloads/dev/certs/localhost-key.pem'),
      cert: fs.readFileSync('/Users/apple/Downloads/dev/certs/localhost.pem'),
      //ca: fs.readFileSync('./certs/my-ca.crt')
    },
    devMiddleware: {
      writeToDisk: true,
    },
    allowedHosts: "all",
  },
  publicPath:
    process.env.NODE_ENV === "production"
      ? process.env.ASSET_PATH || "/"
      : `https://localhost:${devPort}/`,
  configureWebpack: {
    output: {
      clean: true,
      filename: `js/wp-spotlight-search-${process.env.VUE_APP_VERSION}.js`,
      hotUpdateChunkFilename: "hot/hot-update.js",
      hotUpdateMainFilename: "hot/hot-update.json",
    },
    optimization: {
      splitChunks: false,
    },
    plugins: production,
  },
  filenameHashing: true,
  css: {
    extract: {
      filename: `css/wp-spotlight-search-${process.env.VUE_APP_VERSION}.css`,
    },
  },
  productionSourceMap: false,
};
