//
// glightbox.js
// Theme module
//

import 'glightbox';
import GLightbox from 'glightbox';

const glightbox = GLightbox({
    selector: '.glightbox'
});

// Make available globally
window.GLightbox = GLightbox;
