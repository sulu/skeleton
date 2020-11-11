const path = require('path');

module.exports = {
    plugins: {
        'postcss-import': {
            path: path.resolve(process.cwd(), 'node_modules'),
        },
        'postcss-nested': {},
        'postcss-simple-vars': {},
        'postcss-calc': {},
        'postcss-hexrgba': {},
        'autoprefixer': {},
    },
};
