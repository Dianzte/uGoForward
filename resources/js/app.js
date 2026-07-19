//
// resources/js/app.js

// Import global styles/scripts
import '../css/app.css'; 

// Example: Only load homepage logic if we are on the homepage
if (document.querySelector('#homepage-element')) {
    import('./homepage.js');
    import('../css/homepage.css');
}