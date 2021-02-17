const path = require('path')
const webpack = require('webpack')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin')
const CopyPlugin = require('copy-webpack-plugin')
const config = require('./config.js');

const externals = {
  react: 'React',
  'react-dom': 'ReactDOM'
}

const copyFiles = () => {
  var copy = [];

  config.paths.forEach(path => {
    copy = copy.concat([
      {
          from: './public/js',
          to: path+'/js',
      },
      {
          from: './public/css',
          to: path+'/css',
      },
    ])
  });

  return copy;
}

module.exports = {
  mode: process.env.NODE_ENV,
  entry: './src/resources/js/gutenberg.js',
  output: {
    filename: 'gutenberg.js',
    path: path.resolve(__dirname, 'public/js')
  },
  devtool: 'source-map',
  externals: externals,
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader'
        }
      },
      {
        test: /\.(s*)css$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader
          },
          'css-loader',
          'postcss-loader',
          'sass-loader'
        ]
      },
      {
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: '../css/fonts/'
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({ filename: '../css/gutenberg.css' }),
    new CssMinimizerPlugin(),
    new CopyPlugin({
      patterns: copyFiles(),
    }),
  ],
}
