// Webpack uses this to work with directories
const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

// This is main configuration object.
// Here you write different options and tell Webpack what to do
module.exports = {

  // Path to your entry point. From this file Webpack will begin his work
  entry: './wp-content/themes/urpg-2020/assets/js/index.js',

  // Path and filename of your result bundle.
  // Webpack will bundle all JavaScript into this file
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'main.js'
  },

  plugins: [
  ],

  module: {
    rules: [
        {
          test: /\.js$/,
          exclude: /(node_modules)/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env']
            }
          }
        },
        {
          test: /\.css$/,
          use: [
            {
              // After all CSS loaders we use plugin to do his work.
              // It gets all transformed CSS and extracts it into separate
              // single bundled file
              loader: MiniCssExtractPlugin.loader
            }, 
            {
              loader: "css-loader"
            },
            {
              loader: "postcss-loader"
            },
          ]
        }
    ]
  },

  plugins: [
    new MiniCssExtractPlugin({
      filename: "main.css"
    })
  
  ],

  // Default mode for Webpack is production.
  // Depending on mode Webpack will apply different things
  // on final bundle. For now we don't need production's JavaScript 
  // minifying and other thing so let's set mode to development
  mode: 'development',
  watch: true,
};