const path = require("path");
module.exports = {
  productionSourceMap: process.env.NODE_ENV === 'production'
  ? false
  : true,
  css: {
    loaderOptions: {
      sass: {
        additionalData: `
          @import "@/scss/_mixins.scss";
          @import "@/scss/_functions.scss";
          @import "@/scss/_variables.scss";
        `
      }
    }
  },
  // tweak internal webpack configuration.
  // see https://github.com/vuejs/vue-cli/blob/dev/docs/webpack.md
  filenameHashing: false,
  publicPath: '/wp-content/plugins/cookie-law-info/admin/dist',
}