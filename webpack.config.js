const path = require('path');
// const webpack = require('webpack');

module.exports = {
    resolve: {
        extensions: ['.js', '.json', '.vue'],
        alias: {
            '@': path.resolve(__dirname, 'resources/js/'),
            '@views': path.resolve(__dirname, 'resources/js/views'),
            '@gql': path.resolve(__dirname, 'resources/js/graphQL'),
            '@kuu': path.resolve(__dirname, 'resources/js/libs/vuetify-kuu'),
        }
    },
};
