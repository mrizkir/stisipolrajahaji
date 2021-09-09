const path = require("path")
const { mergeSassVariables } = require("@vuetify/cli-plugin-utils")

module.exports = {
  publicPath: "/",
  productionSourceMap: false,
  transpileDependencies: ["vuetify"],
  configureWebpack: {
    resolve: {
      alias: {        
        "@core": path.resolve(__dirname, "src/@core"),        
        "@user-variables": path.resolve(__dirname, "src/styles/variables.scss"),
      },
    },
  },
  chainWebpack: config => {
    const modules = ["vue-modules", "vue", "normal-modules", "normal"]
    modules.forEach(match => {
      config.module
        .rule("sass")
        .oneOf(match)
        .use("sass-loader")
        .tap(opt => mergeSassVariables(opt, "'@/styles/variables.scss'"))
      config.module
        .rule("scss")
        .oneOf(match)
        .use("sass-loader")
        .tap(opt => mergeSassVariables(opt, "'@/styles/variables.scss';"))
    })
  },
}
