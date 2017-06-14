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
                    presets: ['babel-preset-react', 'babel-preset-es2015'],
                },
            },
        ],
    },
    plugins: [
        new CleanObsoleteChunksPlugin(),
        new ManifestPlugin(),
    ],
}
