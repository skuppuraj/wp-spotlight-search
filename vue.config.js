const devPort = 8081;
process.env.VUE_APP_VERSION = require('./package.json').version;

module.exports = {
  devServer: {
    hot: true,
    writeToDisk: true,
    liveReload: false,
    sockPort: devPort,
    port: devPort,
    headers: { "Access-Control-Allow-Origin": "*" }
  },
  publicPath:
    process.env.NODE_ENV === "production"
      ? process.env.ASSET_PATH || "/"
      : `http://localhost:${devPort}/`,
  configureWebpack: {
    output: {
      filename: `app-`+process.env.VUE_APP_VERSION+`.js`,
      hotUpdateChunkFilename: "hot/hot-update.js",
      hotUpdateMainFilename: "hot/hot-update.json"
    },
    optimization: {
      splitChunks: false
    }
  },
  filenameHashing: true,
  css: {
    extract: {
      filename: `app-`+process.env.VUE_APP_VERSION+`.css`
    }
  }
};