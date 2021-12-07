module.exports = {
  chainWebpack: config => {
    config.plugin("provide").use(require("webpack").ProvidePlugin, [
      {
        $: "jquery",
        jQuery: "jquery",
        Popper: ["popper.js", "default"],        
      }
    ]);
  }
};