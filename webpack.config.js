const isProduction = process.env.NODE_ENV === 'production';

const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );
const postcssPlugins = require('@wordpress/postcss-plugins-preset');
const path = require("path");
const {hasBabelConfig, hasPostCSSConfig, hasCssnanoConfig} = require("@wordpress/scripts/utils");
const MiniCSSExtractPlugin = require( 'mini-css-extract-plugin' );
const HtmlWebpackPlugin = require('html-webpack-plugin');

const cssLoaders = [
  MiniCSSExtractPlugin.loader,
  {
    loader: 'css-loader',
    options: {
      url: false
    },
  },
  {
    loader: require.resolve( 'postcss-loader' ),
    options: {
        postcssOptions: {
          ident: 'postcss',
          sourceMap: !isProduction,
          plugins: isProduction
            ? [
              // will throw this err -> (8013:3) autoprefixer: grid-auto-rows is not supported by IE
              //...postcssPlugins,
              require('cssnano')({
                // Provide a fallback configuration if there's not
                // one explicitly available in the project.
                ...(!hasCssnanoConfig() && {
                  preset: [
                    'default',
                    {
                      discardComments: {
                        removeAll: true,
                      },
                    },
                  ],
                }),
              }),
            ]
            : '',
            // : postcssPlugins,
        }
    },
  },
];

defaultConfig.module = {
  rules : [
    {
      test: /\.(j|t)sx?$/,
      exclude: /node_modules/,
      use: [
        {
          loader: require.resolve('babel-loader'),
          options: {
            // Babel uses a directory within local node_modules
            // by default. Use the environment variable option
            // to enable more persistent caching.
            cacheDirectory:
              process.env.BABEL_CACHE_DIRECTORY || true,

            // Provide a fallback configuration if there's not
            // one explicitly available in the project.
            ...(!hasBabelConfig() && {
              babelrc: false,
              configFile: false,
              presets: [
                require.resolve(
                  '@wordpress/babel-preset-default'
                ),
              ]
            }),
          },
        },
      ],
    },
    {
      test: /\.css$/,
      use: cssLoaders,
    },
    {
      test: /\.s[ac]ss$/i,
      use: [
        ...cssLoaders,
        {
          loader: require.resolve( 'sass-loader' ),
          options: {
            sourceMap: ! isProduction,
          },

        },
      ],
    },
    {
      test: /\.svg$/,
      issuer: /\.(sc|sa|c)ss$/,
      type: 'asset/inline',
    },
    {
      test: /\.(png|jpg|jpeg|gif)$/i,
      type: 'asset/resource',
    },
    {
      test: /\.(woff|woff2|eot|ttf|otf)$/i,
      type: 'asset/resource',
    }
  ]
}

const config = {
  ...defaultConfig,
  context: __dirname,
  output: {
    path: path.resolve(__dirname, 'dist')
  },
  plugins: [
    new CleanWebpackPlugin( {
      cleanStaleWebpackAssets: false,
    } ),
    new HtmlWebpackPlugin({
      title: 'feature-notifications',
      favicon: "./includes/ui/notification-hub/assets/img/favicon.ico",
      template: './includes/ui/notification-hub/assets/template.html'
    }),
    // MiniCSSExtractPlugin to extract the CSS thats gets imported into JavaScript.
    new MiniCSSExtractPlugin( {
      filename: '[name].css'
    } ),
  ],
};

if ( ! isProduction ) {
  // WP_DEVTOOL global variable controls how source maps are generated.
  // See: https://webpack.js.org/configuration/devtool/#devtool.
  config.devtool = process.env.WP_DEVTOOL || 'source-map';
  config.module.rules.unshift( {
    test: /\.(j|t)sx?$/,
    exclude: [ /node_modules/ ],
    use: require.resolve( 'source-map-loader' ),
    enforce: 'pre',
  } );
  config.devServer = {
    devMiddleware: {
      writeToDisk: true,
    },
    allowedHosts: 'auto',
    host: 'localhost',
    port: 8887,
    open: true,
    proxy: {
      './build': {
        pathRewrite: {
          '^/build': '',
        },
      },
    },
  };
}

module.exports = config;
