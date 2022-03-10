const isProduction = process.env.NODE_ENV === 'production';

const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );
const path = require("path");
const {hasBabelConfig, hasCssnanoConfig} = require("@wordpress/scripts/utils");
const MiniCSSExtractPlugin = require( 'mini-css-extract-plugin' );
const HtmlWebpackPlugin = require('html-webpack-plugin');

// https://github.com/webpack-contrib/html-loader/issues/291#issuecomment-721909576
const INCLUDE_PATTERN = /<include src="(.+)"\s*\/?>(?:<\/html>)?/gi
const processNestedHtml = (content, loaderContext, dir = null) =>
  !INCLUDE_PATTERN.test(content) ? content : content.replace(INCLUDE_PATTERN, (m, src) => {
    const filePath = path.resolve(dir || loaderContext.context, src)
    loaderContext.dependency(filePath)
    return processNestedHtml(loaderContext.fs.readFileSync(filePath, 'utf8'), loaderContext, path.dirname(filePath))
  })

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
      test: /\.html$/,
      loader: 'html-loader',
      include: [
        path.resolve(__dirname, "includes/ui/notification-hub/assets")
      ],
      options: {
        preprocessor: processNestedHtml
      }
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
  output: {
    path: path.resolve(__dirname, 'docs')
  },
  plugins: [
    new CleanWebpackPlugin( {
      cleanStaleWebpackAssets: false,
    } ),
    new HtmlWebpackPlugin({
      title: 'feature-notifications',
      filename:'index.html',
      favicon: "./includes/ui/notification-hub/assets/img/favicon.ico",
      template: './includes/ui/notification-hub/assets/template.html'
    }),
    new HtmlWebpackPlugin({
      title: 'feature-notifications',
      filename:'settings.html',
      favicon: "./includes/ui/notification-hub/assets/img/favicon.ico",
      template: './includes/ui/notification-hub/assets/settings.html'
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
      './docs': {
        pathRewrite: {
          '^/docs': '',
        },
      },
    },
  };
}

module.exports = config;
