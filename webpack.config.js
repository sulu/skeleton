const webpack = require('webpack');
const glob = require('glob');
const path = require('path');
const CleanObsoleteChunksPlugin = require('webpack-clean-obsolete-chunks');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');

const entries = glob.sync('./vendor/**/Resources/js/index.js');
const basePath = 'adminV2';

entries.unshift('whatwg-fetch');

module.exports = {
    entry: entries,
    output: {
        path: path.resolve(__dirname, 'web'),
        filename: basePath + '/[name].[chunkhash].js',
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                query: {
                    presets: ['react', 'es2015'],
                    plugins: ['transform-decorators-legacy', 'transform-class-properties', 'transform-object-rest-spread'],
                },
            },
            {
                test: /\.(scss)$/,
                use: ExtractTextPlugin.extract({
                    use: [
                        {
                            loader: 'css-loader',
                            options: {
                                modules: true,
                                importLoaders: 1,
                            },
                        },
                        'postcss-loader',
                    ],
                }),
            },
            {
                test: /\.css/,
                use: ExtractTextPlugin.extract({
                    use: [
                        {
                            loader: 'css-loader',
                            options: {
                                modules: false,
                            },
                        },
                    ],
                }),
            },
            {
                test:/\.(svg|ttf|woff|woff2|eot)(\?.*$|$)/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '/' + basePath + '/fonts/[name].[hash].[ext]',
                        },
                    },
                ],
            },
        ],
    },
    plugins: [
        new CleanObsoleteChunksPlugin(),
        new ManifestPlugin({
            fileName: basePath + '/manifest.json',
        }),
        new ExtractTextPlugin(basePath + '/main.[hash].css'),
        new webpack.ProvidePlugin({
            'Promise': 'promise-polyfill',
        })
    ],
}
