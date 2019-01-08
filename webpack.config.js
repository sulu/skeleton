const webpackConfig = require('./vendor/sulu/sulu/webpack.config.js');

module.exports = (env, argv) => {
    env = env ? env : {};
    argv = argv ? argv : {};

    env.root_path = __dirname;

    const config = webpackConfig(env, argv);

    // For adding custom admin js file uncomment the following line:
    // config.entry.unshift(path.resolve(__dirname, 'assets/admin/index.js'));

    return config;
};
