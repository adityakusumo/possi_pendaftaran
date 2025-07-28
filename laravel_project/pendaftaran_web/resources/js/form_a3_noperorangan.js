// resources/js/form_a3_nomor_perorangan.js

$(document).ready(function () {
    console.log('Document ready. Initializing Form A3 Nomor Perorangan scripts.');

    // Get references to elements
    const namaAtletSelect = $('#nama_atlet_input'); // The dropdown for athlete names
    const pilihAtletButton = $('#pilih-atlet-button'); // The "Pilih" button
    const genderPaRadio = $('#gender_pa'); // Radio button for Pria (Male)
    const genderPiRadio = $('#gender_pi'); // Radio button for Wanita (Female)
    const kuSelect = $('#ku_select'); // KU dropdown
    const spYesRadio = $('#sp_yes'); // SP radio button
    const spNoRadio = $('#sp_no'); // Bukan SP radio button
    const namaClubInput = $('#nama_club');
    const kotaKabInput = $('#kota_kab');
    const namaKotaKabInput = $('#nama_kota_kab');
    const propinsiInput = $('#propinsi');
    const negaraInput = $('#negara');

    const timeInputsContainer = $('#time_inputs_container'); // The div containing checkbox and time fields
    const enableTimeInputCheckbox = $('#enable_time_input'); // The checkbox
    const timeFieldsDiv = $('#time_fields'); // The div containing MM, SS, HS inputs
    const mmInput = $('#mm_input');
    const ssInput = $('#ss_input');
    const hsInput = $('#hs_input');


    // Global variable expected from Blade: window.atletDetails
    // This object will contain all athlete details, keyed by their IDATLET.
    const allAtletDetails = window.atletDetails || {};
    console.log('Loaded allAtletDetails:', allAtletDetails);

    // Function to handle input for MM, SS, HS to allow only 2-digit integers
    function enforceTwoDigitInteger(event) {
        const input = $(this);
        let value = input.val();

        // Allow backspace, delete, arrow keys, tab
        if (event.key === 'Backspace' || event.key === 'Delete' ||
            event.key.startsWith('Arrow') || event.key === 'Tab') {
            return;
        }

        // Allow only digits
        if (!/^\d$/.test(event.key)) {
            event.preventDefault();
            return;
        }

        // If value is already 2 digits, prevent further input
        if (value.length >= 2) {
            event.preventDefault();
            return;
        }
    }

    // Attach input validation to MM, SS, HS fields
    mmInput.on('keydown', enforceTwoDigitInteger);
    ssInput.on('keydown', enforceTwoDigitInteger);
    hsInput.on('keydown', enforceTwoDigitInteger);

    // Event listener for the "Pilih" button
    pilihAtletButton.on('click', function () {
        // Get the IDATLET from the selected option
        const selectedIdAtlet = namaAtletSelect.val();

        if (!selectedIdAtlet) {
            alert('Silakan pilih atlet dari daftar terlebih dahulu.');
            return;
        }

        // Look up the athlete details using IDATLET
        const athlete = allAtletDetails[selectedIdAtlet];
        console.log('Attempting to retrieve athlete with IDATLET:', selectedIdAtlet);
        console.log('Retrieved athlete object:', athlete);

        if (athlete) {
            console.log('Selected athlete details:', athlete);

            // 1. Set Gender Radio Button
            genderPaRadio.prop('checked', false); // Clear both first
            genderPiRadio.prop('checked', false);
            if (athlete.GENDER && athlete.GENDER.toUpperCase() === 'PA') {
                genderPaRadio.prop('checked', true);
            } else if (athlete.GENDER && athlete.GENDER.toUpperCase() === 'PI') {
                genderPiRadio.prop('checked', true);
            }

            // 2. Set KU Select (if KU is available in athlete data)
            if (athlete.KU) {
                kuSelect.val(athlete.KU);
            } else {
                kuSelect.val(''); // Clear if no KU data
            }

            // 3. Set SP Status Radio Button
            spYesRadio.prop('checked', false);
            spNoRadio.prop('checked', false);
            // Assuming SP column contains 'Y' for SP and anything else for Bukan SP
            if (athlete.SP && (athlete.SP.toUpperCase() === '1' || athlete.SP.toUpperCase() === 'SP')) {
                spYesRadio.prop('checked', true);
            } else {
                spNoRadio.prop('checked', true); // Default to Bukan SP if not SP
            }

            // 4. Auto-fill Club/Location Details (if you want to override the initial values)
            // You might want to keep these readonly fields as they are, representing the user's kontingen.
            // If you want them to reflect the *athlete's* original club/location:
            namaClubInput.val(athlete.NAMACLUB || '');
            kotaKabInput.val(athlete.JENISDOM || '');
            namaKotaKabInput.val(athlete.NAMAKOTADOM || '');
            propinsiInput.val(athlete.NAMAPROPDOM || '');
            negaraInput.val(athlete.NEGARA || 'INDONESIA'); // Uncomment if NEGARA is in Atlet table
            // NEW: Show only the checkbox container when an athlete is selected
            timeInputsContainer.removeClass('hidden');
            enableTimeInputCheckbox.prop('checked', false); // Ensure checkbox is unchecked by default
            timeFieldsDiv.addClass('hidden'); // Ensure time fields are hidden initially
            mmInput.val(''); ssInput.val(''); hsInput.val(''); // Clear any previous values

        } else {
            alert('Detail atlet tidak ditemukan untuk pilihan ini.');
            // Clear fields if athlete not found
            genderPaRadio.prop('checked', false);
            genderPiRadio.prop('checked', false);
            kuSelect.val('');
            spYesRadio.prop('checked', false);
            spNoRadio.prop('checked', false);
            namaClubInput.val('');
            kotaKabInput.val('');
            namaKotaKabInput.val('');
            propinsiInput.val('');
            negaraInput.val('INDONESIA'); // Reset or clear as appropriate
            timeInputsContainer.addClass('hidden');
            enableTimeInputCheckbox.prop('checked', false);
            timeFieldsDiv.addClass('hidden');
            mmInput.val(''); ssInput.val(''); hsInput.val('');
        }
    });

    // NEW: Event listener for the checkbox
    enableTimeInputCheckbox.on('change', function () {
        if ($(this).is(':checked')) {
            timeFieldsDiv.removeClass('hidden'); // Show MM, SS, HS fields
        } else {
            timeFieldsDiv.addClass('hidden'); // Hide MM, SS, HS fields
            mmInput.val(''); ssInput.val(''); hsInput.val(''); // Clear values when hidden
        }
    });

    // Optional: Auto-select on dropdown change (instead of button click)
    /*
    namaAtletSelect.on('change', function() {
        pilihAtletButton.click(); // Simulate a click on the Pilih button
    });
    */
});
