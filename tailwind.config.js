const defaultTheme = require( 'tailwindcss/defaultTheme' );

module.exports = {
    purge: [
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [ 'Open Sans', ...defaultTheme.fontFamily.sans ],
            }
        },
    },

    variants: {
        extend: {
            opacity: [ 'disabled' ],
        },
    },

    plugins: [
        require( 'postcss-import' ),
        require( '@tailwindcss/forms' )
    ],
};
