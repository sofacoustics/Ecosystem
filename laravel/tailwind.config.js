import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** get access to .env varaibles including expansion */
const dotenv = require('dotenv');
const dotenvExpand = require('dotenv-expand');
const myEnv = dotenv.config();
dotenvExpand.expand(myEnv);
/** get COLOR_LIST */
const colors = process.env.COLOR_LIST ? process.env.COLOR_LIST.split(',') : [];

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/View/Components/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

	safelist: [
		/** 
		 *	Safelist all colors that you would like to use in View:share variables,
		 *	since they won't be picked up by npm run build otherwise. 
		 */
		{ 
			pattern: new RegExp(`(bg|border)-(${colors.join('|')})-(100|300|500|700|900)`) ,
			variants: ['hover', 'focus'],
		}
	],

    plugins: [forms],
};
