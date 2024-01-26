/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import 'bulma-carousel/dist/css/bulma-carousel.min.css'

// custom js file
import './js/burger';
import './js/js-cloudimage-360-view'; 
import 'bulma-carousel/dist/js/bulma-carousel';

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

// start the Stimulus application
import './bootstrap';

bsCustomFileInput.init();