import './bootstrap';

// Import your custom script AFTER other dependencies
import './script';

import './form_a1_namaatlet'

// import './form_a3_noperorangan'

// import './form_a3_noestafet'

if (window.location.pathname.includes('/form-a3/nomor-perorangan')) {
    import('./form_a3_noperorangan.js');
} else if (window.location.pathname.includes('/form-a3/nomor-estafet')) {
    import('./form_a3_noestafet.js');
}

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

