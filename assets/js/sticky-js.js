//
// sticky-js.js
// Theme module
//

import Sticky from 'sticky-js';

var sb = e.select('[data-sticky]');
if (e.isVariableDefined(sb)) {
    var sticky = new Sticky('[data-sticky]');
}

// Make available globally
window.Sticky = Sticky;