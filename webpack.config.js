const path = require('path');

module.exports = {
    entry: {
        "audio-player": './src/assets/js/shortcodes/audio-player.js'
    },
    output: {
        filename: '[name].js',
        // publicPath: '/js/', // public path is defined dynamically based on the installation folder
        path: path.resolve(__dirname, 'enfold/shortcodes/') + '/audio-player/'
    },
    devtool: 'source-map',
    plugins: [],
    module: {
        rules: [
            {
                test: /\.js?/,
                use: 'babel-loader',
                exclude: /node_modules/
            }
        ]
    }
};
