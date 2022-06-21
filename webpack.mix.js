const mix         = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.setPublicPath('public');
mix.setResourceRoot('../');
mix.disableNotifications();

mix.js('./assets/hierarchy/FrontPage/FrontPage.js', 'public/js')
    .extract()
    .sass('./assets/hierarchy/FrontPage/FrontPage.scss', 'public/css')
    .options({
        postCss: [tailwindcss('./tailwind/FrontPage.config.js')],
    });

/*!wp-cli component create placeholder!*/