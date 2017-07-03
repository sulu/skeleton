const glob = require('glob');
const path = require('path');
const CleanObsoleteChunksPlugin = require('webpack-clean-obsolete-chunks');
const ManifestPlugin = require('webpack-manifest-plugin');

const entries = glob.sync('./vendor/**/Resources/js/index.js');

module.exports = {
    entry: entries,
    output: {
        path: path.resolve(__dirname, 'web'),
        filename: '[name].[chunkhash].js',
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
                test: /\.(scss|css)$/,
                use: [
                    'style-loader',
                    {
                        loader: 'css-loader',
                        options: {
                            modules: true,
                            importLoaders: 1,
                        },
                    },
                    'postcss-loader',
                ],
            },
            {
                test:/\.(svg|ttf|woff|woff2|eot)$/,
                use: [
                    'url-loader',
                ],
            }
        ],
    },
    plugins: [
        new CleanObsoleteChunksPlugin(),
        new ManifestPlugin(),
    ],
}
