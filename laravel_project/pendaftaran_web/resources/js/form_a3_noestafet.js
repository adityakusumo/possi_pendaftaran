$(document).ready(function () {
    console.log('Document ready. Initializing Form A3 Nomor Estafet scripts.');

    // Get references to elements
    const namaReguInput = $('#nama_regu'); // The dropdown for athlete names
    const pilihReguButton = $('#pilih-regu-button'); // The "Pilih" button
    const genderPaRadioEstafet = $('#gender_estafet_pa'); // Radio button for Pria (Male)
    const genderPiRadioEstafet = $('#gender_estafet_pi'); // Radio button for Wanita (Female)
    const kuSelectEstafet = $('#ku_select_estafet'); // KU dropdown
    const spYesRadio = $('#sp_yes'); // SP radio button
    const spNoRadio = $('#sp_no'); // Bukan SP radio button
    const spStatusEstafet = $('input[name="sp_status_estafet"]');
    const namaClubInput = $('#nama_club');
    const kotaKabInput = $('#kota_kab');
    const namaKotaKabInput = $('#nama_kota_kab');
    const propinsiInput = $('#propinsi');
    const negaraInput = $('#negara');

    // Simpan button reference
    const simpanButton = $('#simpan-button');
    const hapusButton = $('#hapus-button');
    const selectedA3IdInput = $('#selected-a3-id');
    const selectedA3NameInput = $('#selected-a3-name');

    // NEW: References for 50m inputs
    const sf4x50mContainer = $('#SF_4x50m_container');
    const sf4x50mEnableCheckbox = $('#SF_4x50m_enable_time_chkbx');
    const sf4x50mTimeFieldsDiv = $('#SF_4x50m_time_fields');
    const sf4x50mMmInput = $('#SF_4x50m_mm_txtbx');
    const sf4x50mSsInput = $('#SF_4x50m_ss_txtbx');
    const sf4x50mHsInput = $('#SF_4x50m_hs_txtbx');
    // NEW: References for 100m inputs
    const sf4x100mContainer = $('#SF_4x100m_container');
    const sf4x100mEnableCheckbox = $('#SF_4x100m_enable_time_chkbx');
    const sf4x100mTimeFieldsDiv = $('#SF_4x100m_time_fields');
    const sf4x100mMmInput = $('#SF_4x100m_mm_txtbx');
    const sf4x100mSsInput = $('#SF_4x100m_ss_txtbx');
    const sf4x100mHsInput = $('#SF_4x100m_hs_txtbx');
    // ADDED: References for 200m inputs
    const sf4x200mContainer = $('#SF_4x200m_container');
    const sf4x200mEnableCheckbox = $('#SF_4x200m_enable_time_chkbx');
    const sf4x200mTimeFieldsDiv = $('#SF_4x200m_time_fields');
    const sf4x200mMmInput = $('#SF_4x200m_mm_txtbx');
    const sf4x200mSsInput = $('#SF_4x200m_ss_txtbx');
    const sf4x200mHsInput = $('#SF_4x200m_hs_txtbx');
    // ADDED: References for 400m inputs
    const sf4x50mMixContainer = $('#SF_4x50mMix_container');
    const sf4x50mMixEnableCheckbox = $('#SF_4x50mMix_enable_time_chkbx');
    const sf4x50mMixTimeFieldsDiv = $('#SF_4x50mMix_time_fields');
    const sf4x50mMixMmInput = $('#SF_4x50mMix_mm_txtbx');
    const sf4x50mMixSsInput = $('#SF_4x50mMix_ss_txtbx');
    const sf4x50mMixHsInput = $('#SF_4x50mMix_hs_txtbx');
    // ADDED: References for 800m inputs
    const sf4x100mMixContainer = $('#SF_4x100mMix_container');
    const sf4x100mMixEnableCheckbox = $('#SF_4x100mMix_enable_time_chkbx');
    const sf4x100mMixTimeFieldsDiv = $('#SF_4x100mMix_time_fields');
    const sf4x100mMixMmInput = $('#SF_4x100mMix_mm_txtbx');
    const sf4x100mMixSsInput = $('#SF_4x100mMix_ss_txtbx');
    const sf4x100mMixHsInput = $('#SF_4x100mMix_hs_txtbx');
    // ADDED: References for 1500m inputs
    const sf4x200mMixContainer = $('#SF_4x200mMix_container');
    const sf4x200mMixEnableCheckbox = $('#SF_4x200mMix_enable_time_chkbx');
    const sf4x200mMixTimeFieldsDiv = $('#SF_4x200mMix_time_fields');
    const sf4x200mMixMmInput = $('#SF_4x200mMix_mm_txtbx');
    const sf4x200mMixSsInput = $('#SF_4x200mMix_ss_txtbx');
    const sf4x200mMixHsInput = $('#SF_4x200mMix_hs_txtbx');


    // NEW: References for 50m inputs
    const bf4x50mContainer = $('#BF_4x50m_container');
    const bf4x50mEnableCheckbox = $('#BF_4x50m_enable_time_chkbx');
    const bf4x50mTimeFieldsDiv = $('#BF_4x50m_time_fields');
    const bf4x50mMmInput = $('#BF_4x50m_mm_txtbx');
    const bf4x50mSsInput = $('#BF_4x50m_ss_txtbx');
    const bf4x50mHsInput = $('#BF_4x50m_hs_txtbx');
    // NEW: References for 100m inputs
    const bf4x100mContainer = $('#BF_4x100m_container');
    const bf4x100mEnableCheckbox = $('#BF_4x100m_enable_time_chkbx');
    const bf4x100mTimeFieldsDiv = $('#BF_4x100m_time_fields');
    const bf4x100mMmInput = $('#BF_4x100m_mm_txtbx');
    const bf4x100mSsInput = $('#BF_4x100m_ss_txtbx');
    const bf4x100mHsInput = $('#BF_4x100m_hs_txtbx');
    // ADDED: References for 200m inputs
    const bf4x200mContainer = $('#BF_4x200m_container');
    const bf4x200mEnableCheckbox = $('#BF_4x200m_enable_time_chkbx');
    const bf4x200mTimeFieldsDiv = $('#BF_4x200m_time_fields');
    const bf4x200mMmInput = $('#BF_4x200m_mm_txtbx');
    const bf4x200mSsInput = $('#BF_4x200m_ss_txtbx');
    const bf4x200mHsInput = $('#BF_4x200m_hs_txtbx');
    // ADDED: References for 400m inputs
    const bf4x50mMixContainer = $('#BF_4x50mMix_container');
    const bf4x50mMixEnableCheckbox = $('#BF_4x50mMix_enable_time_chkbx');
    const bf4x50mMixTimeFieldsDiv = $('#BF_4x50mMix_time_fields');
    const bf4x50mMixMmInput = $('#BF_4x50mMix_mm_txtbx');
    const bf4x50mMixSsInput = $('#BF_4x50mMix_ss_txtbx');
    const bf4x50mMixHsInput = $('#BF_4x50mMix_hs_txtbx');
    // ADDED: References for 800m inputs
    const bf4x100mMixContainer = $('#BF_4x100mMix_container');
    const bf4x100mMixEnableCheckbox = $('#BF_4x100mMix_enable_time_chkbx');
    const bf4x100mMixTimeFieldsDiv = $('#BF_4x100mMix_time_fields');
    const bf4x100mMixMmInput = $('#BF_4x100mMix_mm_txtbx');
    const bf4x100mMixSsInput = $('#BF_4x100mMix_ss_txtbx');
    const bf4x100mMixHsInput = $('#BF_4x100mMix_hs_txtbx');
    // ADDED: References for 1500m inputs
    const bf4x200mMixContainer = $('#BF_4x200mMix_container');
    const bf4x200mMixEnableCheckbox = $('#BF_4x200mMix_enable_time_chkbx');
    const bf4x200mMixTimeFieldsDiv = $('#BF_4x200mMix_time_fields');
    const bf4x200mMixMmInput = $('#BF_4x200mMix_mm_txtbx');
    const bf4x200mMixSsInput = $('#BF_4x200mMix_ss_txtbx');
    const bf4x200mMixHsInput = $('#BF_4x200mMix_hs_txtbx');

    // Add these logs immediately after defining the variables
    console.log('JS Initial Check: sf4x50mContainer found?', sf4x50mContainer.length > 0 ? 'Yes' : 'No', sf4x50mContainer);
    console.log('JS Initial Check: sf4x100mContainer found?', sf4x100mContainer.length > 0 ? 'Yes' : 'No', sf4x100mContainer);
    // ADDED: Logs for 200m and 400m containers
    console.log('JS Initial Check: sf4x200mContainer found?', sf4x200mContainer.length > 0 ? 'Yes' : 'No', sf4x200mContainer);
    console.log('JS Initial Check: sf4x50mMixContainer found?', sf4x50mMixContainer.length > 0 ? 'Yes' : 'No', sf4x50mMixContainer);


    // Global variable expected from Blade: window.atletDetails
    // This object will contain all athlete details, keyed by their IDATLET.
    const allAtletDetails = window.atletDetails || {};
    console.log('Loaded allAtletDetails:', allAtletDetails);
    const currentUserEmail = window.currentUserEmail || ''; // Get current user's email
    const existingA3Entries = window.existingA3Entries || [];
    console.log('Loaded existingA3Entries:', existingA3Entries);

    // Helper function to format date to YYYY-MM-DD (for consistency with DB DATE type)
    function formatDateToYYYYMMDD(isoDateString) {
        if (!isoDateString) return '';
        const date = new Date(isoDateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // --- NEW FUNCTION TO POPULATE ATHLETE DROPDOWN BASED ON EXISTING ENTRIES ---
    function populateAtletDropdown() {
        namaReguSelect.empty(); // Clear existing options
        namaReguSelect.append($('<option>', { value: '', text: 'Pilih Atlet' })); // Add default option

        // Create a Set of unique keys for athletes already in A3 for this user
        // The key must match the logic in your controller's updateOrCreate method.
        // NAMAATLET, GENDER, TGLLAHIR, email
        const existingA3AthleteKeys = new Set();
        existingA3Entries.forEach(entry => {
            // Create a consistent key string for matching
            const key = `${entry.NAMAATLET}_${entry.GENDER}_${formatDateToYYYYMMDD(entry.TGLLAHIR)}_${entry.email}`;
            existingA3AthleteKeys.add(key);
        });

        // Filter and add athletes to the dropdown
        for (const idAtlet in allAtletDetails) {
            const athlete = allAtletDetails[idAtlet];
            // Create the same consistent key string for the current athlete
            const athleteKey = `${athlete.NAMAATLET}_${athlete.GENDER}_${formatDateToYYYYMMDD(athlete.TGLLAHIR)}_${currentUserEmail}`;

            // Only add if this athlete is NOT in the existing A3 entries for this user
            if (!existingA3AthleteKeys.has(athleteKey)) {
                namaReguSelect.append($('<option>', {
                    value: athlete.IDATLET,
                    text: `${athlete.NAMAATLET} (${athlete.GENDER} / ${athlete.KU})`
                }));
            }
        }
    }

    // Call this function when the page loads to populate the dropdown initially
    // populateAtletDropdown();

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

    // Reusable function to manage checkbox and associated time fields
    function setupTimeInputGroup(containerDiv, enableCheckbox, timeFieldsDiv, mmInput, ssInput, hsInput) {
        // Attach input validation to MM, SS, HS fields
        mmInput.on('keydown', enforceTwoDigitInteger);
        ssInput.on('keydown', enforceTwoDigitInteger);
        hsInput.on('keydown', enforceTwoDigitInteger);

        // Event listener for the checkbox
        enableCheckbox.on('change', function () {
            if ($(this).is(':checked')) {
                timeFieldsDiv.removeClass('hidden'); // Show MM, SS, HS fields
            } else {
                timeFieldsDiv.addClass('hidden'); // Hide MM, SS, HS fields
                mmInput.val(''); ssInput.val(''); hsInput.val(''); // Clear values when hidden
            }
        });

        // Function to reset the state (hide all, uncheck checkbox, clear values)
        return function resetGroup() {
            enableCheckbox.prop('checked', false);
            timeFieldsDiv.addClass('hidden');
            mmInput.val(''); ssInput.val(''); hsInput.val('');
        };
    }

    // Reusable function to get and validate time input data for a specific group
    // Returns an object { mm, ss, hs, isValid, errorMessage }
    function processTimeInputGroupData(prefix, enableCheckbox, mmInput, ssInput, hsInput) {
        let mm = mmInput.val().trim();
        let ss = ssInput.val().trim();
        let hs = hsInput.val().trim();
        let isValid = true;
        let errorMessage = '';

        if (enableCheckbox.is(':checked')) {
            // Check for all null
            if (mm === '' && ss === '' && hs === '') {
                mm = '99';
                ss = '99';
                hs = '99';
            } else {
                // Handle partial nulls (set to 0)
                mm = mm === '' ? '0' : mm;
                ss = ss === '' ? '0' : ss;
                hs = hs === '' ? '0' : hs;

                // Validate SS value
                if (parseInt(ss) < 1) {
                    isValid = false;
                    errorMessage = `Input waktu ${prefix} tidak boleh kurang dari 1 detik!`;
                }
            }
        } else {
            // If checkbox is not checked, values should be null/empty
            mm = '';
            ss = '';
            hs = '';
        }

        return { mm: mm, ss: ss, hs: hs, isValid: isValid, errorMessage: errorMessage };
    }

    // Function to toggle visibility and state of a time input group
    // This function is used by the table click event and the new Pilih button logic
    function populateTimeInputGroup(prefix, mmValue, ssValue, hsValue) {
        const containerId = `#${prefix}_container`;
        const checkboxId = `#${prefix}_enable_time_chkbx`;
        const timeFieldsDivId = `#${prefix}_time_fields`; // This is the div containing MM, SS, HS
        const mmInputId = `#${prefix}_mm_txtbx`;
        const ssInputId = `#${prefix}_ss_txtbx`;
        const hsInputId = `#${prefix}_hs_txtbx`;

        // Check if any value is present (not null or empty string)
        if (mmValue !== null && mmValue !== '' ||
            ssValue !== null && ssValue !== '' ||
            hsValue !== null && hsValue !== '') {

            $(containerId).removeClass('hidden');
            $(checkboxId).prop('checked', true);
            $(timeFieldsDivId).removeClass('hidden'); // Ensure the MM/SS/HS wrapper is visible

            $(mmInputId).val(mmValue);
            $(ssInputId).val(ssValue);
            $(hsInputId).val(hsValue);

            $(mmInputId).prop('disabled', false);
            $(ssInputId).prop('disabled', false);
            $(hsInputId).prop('disabled', false);
        } else {
            // If no values, hide and clear the group
            $(containerId).addClass('hidden');
            $(checkboxId).prop('checked', false);
            $(timeFieldsDivId).addClass('hidden');
            $(mmInputId).val('');
            $(ssInputId).val('');
            $(hsInputId).val('');
            $(mmInputId).prop('disabled', true);
            $(ssInputId).prop('disabled', true);
            $(hsInputId).prop('disabled', true);
        }
    }

    // Setup all time input groups
    const resetSf4x50mGroupContents = setupTimeInputGroup(sf4x50mContainer, sf4x50mEnableCheckbox, sf4x50mTimeFieldsDiv, sf4x50mMmInput, sf4x50mSsInput, sf4x50mHsInput);
    const resetSf4x100mGroupContents = setupTimeInputGroup(sf4x100mContainer, sf4x100mEnableCheckbox, sf4x100mTimeFieldsDiv, sf4x100mMmInput, sf4x100mSsInput, sf4x100mHsInput);
    const resetSf4x200mGroupContents = setupTimeInputGroup(sf4x200mContainer, sf4x200mEnableCheckbox, sf4x200mTimeFieldsDiv, sf4x200mMmInput, sf4x200mSsInput, sf4x200mHsInput);
    const resetSf4x50mMixGroupContents = setupTimeInputGroup(sf4x50mMixContainer, sf4x50mMixEnableCheckbox, sf4x50mMixTimeFieldsDiv, sf4x50mMixMmInput, sf4x50mMixSsInput, sf4x50mMixHsInput);
    const resetSf4x100mMixGroupContents = setupTimeInputGroup(sf4x100mMixContainer, sf4x100mMixEnableCheckbox, sf4x100mMixTimeFieldsDiv, sf4x100mMixMmInput, sf4x100mMixSsInput, sf4x100mMixHsInput);
    const resetSf4x200mMixGroupContents = setupTimeInputGroup(sf4x200mMixContainer, sf4x200mMixEnableCheckbox, sf4x200mMixTimeFieldsDiv, sf4x200mMixMmInput, sf4x200mMixSsInput, sf4x200mMixHsInput);

    // Setup all time input groups
    const resetBf4x50mGroupContents = setupTimeInputGroup(bf4x50mContainer, bf4x50mEnableCheckbox, bf4x50mTimeFieldsDiv, bf4x50mMmInput, bf4x50mSsInput, bf4x50mHsInput);
    const resetBf4x100mGroupContents = setupTimeInputGroup(bf4x100mContainer, bf4x100mEnableCheckbox, bf4x100mTimeFieldsDiv, bf4x100mMmInput, bf4x100mSsInput, bf4x100mHsInput);
    const resetBf4x200mGroupContents = setupTimeInputGroup(bf4x200mContainer, bf4x200mEnableCheckbox, bf4x200mTimeFieldsDiv, bf4x200mMmInput, bf4x200mSsInput, bf4x200mHsInput);
    const resetBf4x50mMixGroupContents = setupTimeInputGroup(bf4x50mMixContainer, bf4x50mMixEnableCheckbox, bf4x50mMixTimeFieldsDiv, bf4x50mMixMmInput, bf4x50mMixSsInput, bf4x50mMixHsInput);
    const resetBf4x100mMixGroupContents = setupTimeInputGroup(bf4x100mMixContainer, bf4x100mMixEnableCheckbox, bf4x100mMixTimeFieldsDiv, bf4x100mMixMmInput, bf4x100mMixSsInput, bf4x100mMixHsInput);
    const resetBf4x200mMixGroupContents = setupTimeInputGroup(bf4x200mMixContainer, bf4x200mMixEnableCheckbox, bf4x200mMixTimeFieldsDiv, bf4x200mMixMmInput, bf4x200mMixSsInput, bf4x200mMixHsInput);

    // List all containers for easy iteration (make sure all are included)
    const allRelayContainers = [
        sf4x50mContainer, sf4x100mContainer, sf4x200mContainer,
        sf4x50mMixContainer, sf4x100mMixContainer, sf4x200mMixContainer,
        bf4x50mContainer, bf4x100mContainer, bf4x200mContainer,
        bf4x50mMixContainer, bf4x100mMixContainer, bf4x200mMixContainer,
    ];

    // Helper function to reset all relay time input groups
    function resetAllRelayTimeInputGroups() {
        allRelayContainers.forEach(container => {
            container.addClass('hidden'); // Hide the main container
            // Find the time fields div within this container and hide it
            container.find('[id$="_time_fields"]').addClass('hidden');
            // Find inputs and clear/disable them
            container.find('input[type="text"]').val('').prop('disabled', true);
            container.find('input[type="checkbox"]').prop('checked', false);
        });
    }

    // Function to set radio button value (re-used from perorangan)
    function setRadioValue(name, value) {
        $(`input[name="${name}"][value="${value}"]`).prop('checked', true);
    }

    // Event listener for the "Pilih" button
    pilihReguButton.on('click', function () {
        const ku = kuSelectEstafet.val();
        const gender = $('input[name="gender_estafet"]:checked').val();
        const namaRegu = namaReguInput.val();

        // --- DEBUGGING CONSOLE LOGS START HERE ---
        console.log('--- Pilih Button Click Debug ---');
        console.log('KU Select Element (jQuery object):', kuSelectEstafet);
        console.log('Raw value from KU select (kuSelectEstafet.val()):', ku);
        console.log('Selected Gender:', gender);
        console.log('Nama Regu:', namaRegu);
        console.log('--------------------------------');
        // --- DEBUGGING CONSOLE LOGS END HERE ---

        // 1. Basic Validation
        if (!ku) {
            alert('Silakan pilih Kelompok Umur (KU) terlebih dahulu.');
            resetAllRelayTimeInputGroups();
            return;
        }
        if (!gender) {
            alert('Silakan pilih Gender terlebih dahulu.');
            resetAllRelayTimeInputGroups();
            return;
        }
        if (!namaRegu.trim()) {
            alert('Silakan isi Nama Regu terlebih dahulu.');
            resetAllRelayTimeInputGroups();
            return;
        }

        // Set default SP status to BUKAN_SP
        setRadioValue('sp_status_estafet', 'BUKAN_SP');


        // 2. Perform an AJAX request to the server
        $.ajax({
            url: '/form-a3/nomor-estafet/get-estafet-events', // NEW endpoint
            method: 'GET', // Use GET as we are fetching data
            data: {
                ku: ku.toUpperCase(), // Send KU as uppercase
                gender: gender.toUpperCase() // Send Gender as uppercase
            },
            success: function (response) {
                console.log('Server response for Estafet Events:', response);

                // Always reset all containers first
                resetAllRelayTimeInputGroups();

                if (response.success && response.events && response.events.length > 0) {
                    alert('Cabang lomba estafet yang tersedia berhasil dimuat.');
                    response.events.forEach(eventName => {
                        // Construct the ID based on the normalized eventName from the server
                        const containerId = `#${eventName}_container`;
                        const container = $(containerId);

                        if (container.length) {
                            container.removeClass('hidden'); // Show the main container
                            // The inner time_fields div is still hidden by default,
                            // it will be revealed when the user checks the checkbox
                            // (handled by setupTimeInputGroup).
                        } else {
                            console.warn(`Container with ID ${containerId} not found in HTML for event: ${eventName}.`);
                        }
                    });
                } else {
                    alert('Tidak ada cabang lomba estafet yang tersedia untuk KU dan Gender yang dipilih.');
                }
            },
            error: function (xhr) {
                console.error('AJAX Error fetching Estafet Events:', xhr.responseText);
                alert('Terjadi kesalahan saat menghubungi server untuk mencari cabang lomba estafet.');
                resetAllRelayTimeInputGroups(); // Reset on error
            }
        });
    });

    // Event listener for the "Simpan" button
    simpanButton.on('click', function (e) {
        e.preventDefault(); // Prevent default form submission

        // const selectedAthleteName = $('#nama_regu').val(); // Get selected name

        const selectedIdAtlet = namaReguSelect.val();
        if (!selectedIdAtlet) {
            alert('Silakan pilih atlet terlebih dahulu sebelum menyimpan.');
            return;
        }

        const athlete = allAtletDetails[selectedIdAtlet];
        if (!athlete) {
            alert('Data atlet tidak ditemukan. Mohon pilih atlet yang valid.');
            return;
        }

        // --- MODIFIED SECTION: Get and convert SP value ---
        // Get the value of the checked radio button (e.g., "SP" or "BUKAN_SP")
        // Assuming your radio buttons have the name 'sp_status'. Adjust if needed.
        const spRadioValue = $('input[name="sp_status"]:checked').val();

        // Map the string value to an integer: 1 for "SP", 0 for "BUKAN_SP"
        const spValueForDatabase = (spRadioValue === 'SP') ? 1 : 0;

        // Log the converted value for debugging
        console.log(`Radio button value is '${spRadioValue}', converted to '${spValueForDatabase}' for the database.`);
        // --- END OF MODIFIED SECTION ---

        // Collect general athlete data
        const formData = {
            _token: $('meta[name="csrf-token"]').attr('content'), // Get CSRF token
            gender: $('input[name="gender"]:checked').val(),
            ku: kuSelect.val(),
            nama_atlet: athlete.NAMAATLET,
            asal: athlete.ASAL, // Assuming ASAL is available in athleteDetails
            nama_club: athlete.NAMACLUB,
            jenis_dom: athlete.JENISDOM,
            nama_kota_dom: athlete.NAMAKOTADOM,
            nama_prop_dom: athlete.NAMAPROPDOM,
            sp: spValueForDatabase, // sp: athlete.SP,
            tgl_lahir: athlete.TGLLAHIR,
            nomor: 'Estafet', // Fixed value as requested
            email: currentUserEmail, // User's email from Blade
        };

        // Process SF 50m time data
        const sf4x50mTimeData = processTimeInputGroupData(
            '50m', sf4x50mEnableCheckbox, sf4x50mMmInput, sf4x50mSsInput, sf4x50mHsInput
        );

        if (!sf4x50mTimeData.isValid) {
            alert(sf4x50mTimeData.errorMessage);
            return;
        }

        // Add SF 50m data to formData
        formData.MON50MM = sf4x50mTimeData.mm;
        formData.MON50SS = sf4x50mTimeData.ss;
        formData.MON50HS = sf4x50mTimeData.hs;

        // You would repeat this for SF 100m, SF 200m, SF 400m, SF 800m, SF 1500m etc.
        // For example:
        // const sf4x100mTimeData = processTimeInputGroupData(
        //     '100m', sf4x100mEnableCheckbox, sf4x100mMmInput, sf4x100mSsInput, sf4x100mHsInput
        // );
        // if (!sf4x100mTimeData.isValid) { alert(sf4x100mTimeData.errorMessage); return; }
        // formData.MON100MM = sf4x100mTimeData.mm;
        // formData.MON100SS = sf4x100mTimeData.ss;
        // formData.MON100HS = sf4x100mTimeData.hs;


        console.log('Data to be sent:', formData); // For debugging

        const athleteNameForConfirmation = formData.nama_atlet; // Get the athlete's name

        const confirmationMessage = `Yakin ingin menyimpan data ${athleteNameForConfirmation}?`;

        if (confirm(confirmationMessage)) { // Show the confirmation popup
            console.log('User confirmed. Data to be sent:', formData); // For debugging AFTER confirmation

            // Send data via AJAX
            $.ajax({
                url: $('#form-a3-estafet').attr('action'), // Get URL from form action
                method: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload(); // Reload page after success
                    } else {
                        // Handle server-side validation errors or custom messages
                        let errorMessage = response.message || 'Gagal menyimpan data.';
                        if (response.errors) {
                            for (const field in response.errors) {
                                errorMessage += '\n' + response.errors[field].join(', ');
                            }
                        }
                        alert(errorMessage);
                        console.error('Server response error:', response);
                    }
                },
                error: function (xhr) {
                    // Handle AJAX request errors (network issues, 500 errors, etc.)
                    console.error('AJAX Error:', xhr.responseText);
                    let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage += '\n' + xhr.responseJSON.message;
                    }
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        for (const key in xhr.responseJSON.errors) {
                            errorMessage += '\n' + xhr.responseJSON.errors[key].join(', ');
                        }
                    }
                    alert(errorMessage);
                }
            });
        } else {
            // User clicked 'Cancel' on the confirmation
            alert('Penyimpanan data dibatalkan.');
            console.log('Data submission cancelled by user.');
        }
    });

    // Function to set radio button value
    function setRadioValue(name, value) {
        $(`input[name="${name}"][value="${value}"]`).prop('checked', true);
    }

    // Function to handle showing/hiding time input group
    function toggleTimeInputGroup(prefix, enable) {
        const containerId = `#${prefix}_container`; //SF_4x50m_container
        const checkboxId = `#${prefix}_enable_time_chkbx`;
        const mmInputId = `#${prefix}_mm_txtbx`;
        const ssInputId = `#${prefix}_ss_txtbx`;
        const hsInputId = `#${prefix}_hs_txtbx`; //SF_4x50m_time_fields

        // !!! THIS IS THE CRUCIAL NEW SELECTOR !!!
        // Assuming the div containing MM, SS, HS has an ID like 'SF_4x50m_time_fields_div'
        // or you can select it relative to the container like:
        // $(containerId).find('.your-time-fields-wrapper-class');
        const timeFieldsDivId = `#${prefix}_time_fields`; // <-- Adjust this ID based on your HTML
        console.log('Toggling container:', containerId, 'and time fields div:', timeFieldsDivId, 'Enable:', enable);


        console.log('Toggling container:', containerId, 'Enable:', enable); // ADD THIS
        console.log('Container element:', $(containerId)); // ADD THIS to see if jQuery finds it


        if (enable) {
            $(containerId).removeClass('hidden'); // Show the container
            $(timeFieldsDivId).removeClass('hidden');
            $(checkboxId).prop('checked', true); // Check the checkbox
            // Enable inputs if they were disabled
            $(mmInputId).prop('disabled', false);
            $(ssInputId).prop('disabled', false);
            $(hsInputId).prop('disabled', false);
        } else {
            $(containerId).addClass('hidden'); // Hide the container
            $(timeFieldsDivId).addClass('hidden');
            $(checkboxId).prop('checked', false); // Uncheck the checkbox
            // Clear inputs and disable them
            $(mmInputId).val('');
            $(ssInputId).val('');
            $(hsInputId).val('');
            $(mmInputId).prop('disabled', true);
            $(ssInputId).prop('disabled', true);
            $(hsInputId).prop('disabled', true);
        }
    }


    // Event listener for table row clicks
    $('#daftarEntriTable').on('click', '.entry-row', function () {
        // Remove 'selected' class from previously selected row
        $('.entry-row').removeClass('bg-blue-100 dark:bg-blue-800');
        // Add 'selected' class to the clicked row
        $(this).addClass('bg-blue-100 dark:bg-blue-800');

        const rowData = $(this).data(); // Get all data- attributes as an object

        console.log('Selected Row Data:', rowData); // For debugging

        // Store the ID and Name in hidden fields
        selectedA3IdInput.val(rowData.idA3);
        selectedA3NameInput.val(rowData.namaAtlet);

        // Populate form fields
        // 1. NAMAATLET to nama_regu (select dropdown)
        // You'll need to update your athlete dropdown based on the retrieved name.
        // If your dropdown is populated with IDATLET as value, you might need to find
        // the corresponding IDATLET from `allAtletDetails` using the NAMAATLET.
        // For simplicity, let's assume `nama_regu` is just a text field for now,
        // or ensure your dropdown has the NAMAATLET as its <option> text.
        // If it's a dropdown, you would do:
        // $('#namaAtlet').val(rowData.namaatlet); // This assumes the option value is NAMAATLET
        // More robust if using IDATLET:
        const selectedAthleteFromTable = Object.values(allAtletDetails).find(
            athlete => athlete.NAMAATLET === rowData.namaatlet && athlete.GENDER === rowData.gender && athlete.TGLLAHIR.startsWith(rowData.tgllahir)
        );
        if (selectedAthleteFromTable) {
            $('#nama_regu').val(selectedAthleteFromTable.IDATLET).trigger('change'); // Trigger change if other fields depend on it
            // The change event on #namaAtlet should ideally populate other fields automatically
            // based on the `allAtletDetails` object.
            // If it doesn't, you need to manually populate them here:
            // This part might be redundant if your existing `namaAtlet` change event already does this.
            setRadioValue('gender', rowData.gender);
            $('#ku_select').val(rowData.ku);
            setRadioValue('sp_status', rowData.sp); // Assuming name="sp_status" for SP radio
            $('#nama_club').val(rowData.namaclub);
            $('#kota_kab').val(rowData.jenisdom);
            $('#nama_kota_kab').val(rowData.namakotadom);
            $('#propinsi').val(rowData.namapropdom);
            $('#negara').val('INDONESIA'); // Fixed value as per requirement

            // Populate time inputs and manage checkbox/visibility
            const mon50mm = rowData.mon50mm;
            const mon50ss = rowData.mon50ss;
            const mon50hs = rowData.mon50hs;

            if (mon50mm !== '' || mon50ss !== '' || mon50hs !== '') { // Check if any part of the time is not empty
                toggleTimeInputGroup('SF_4x50m', true); // Show and check
                $('#SF_4x50m_mm_txtbx').val(mon50mm);
                $('#SF_4x50m_ss_txtbx').val(mon50ss);
                $('#SF_4x50m_hs_txtbx').val(mon50hs);
            } else {
                toggleTimeInputGroup('SF_4x50m', false); // Hide and uncheck
            }

            // You would repeat this for other distances (MON100, MON200, etc.)
            // Example for MON100:
            // const mon100mm = rowData.mon100mm;
            // const mon100ss = rowData.mon100ss;
            // const mon100hs = rowData.mon100hs;
            // if (mon100mm !== '' || mon100ss !== '' || mon100hs !== '') {
            //     toggleTimeInputGroup('SF_4x100m', true);
            //     $('#SF_4x100m_mm_txtbx').val(mon100mm);
            //     $('#SF_4x100m_ss_txtbx').val(mon100ss);
            //     $('#SF_4x100m_hs_txtbx').val(mon100hs);
            // } else {
            //     toggleTimeInputGroup('SF_4x100m', false);
            // }


        } else {
            console.warn("Athlete not found in `allAtletDetails` for selected table row.", rowData.namaatlet);
            // You might want to clear the form or show a message here
        }
    });

    // --- Event listener for the HAPUS button ---
    hapusButton.on('click', function (e) {
        e.preventDefault();

        const idA3 = selectedA3IdInput.val();
        const namaAtlet = selectedA3NameInput.val();

        // Check if an entry is selected
        if (!idA3) {
            alert('Mohon pilih data atlet dari tabel di samping untuk dihapus.');
            return;
        }

        // Use SweetAlert2 for a better confirmation UI (install via npm or CDN)
        Swal.fire({
            title: 'Anda yakin?',
            html: `Anda yakin ingin menghapus data <b>${namaAtlet}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Red for 'YA'
            cancelButtonColor: '#3085d6', // Blue for 'TIDAK'
            confirmButtonText: 'YA, Hapus!',
            cancelButtonText: 'TIDAK'
        }).then((result) => {
            if (result.isConfirmed) {
                // User clicked YA, proceed with AJAX deletion
                $.ajax({
                    url: `/form-a3/delete-estafet/${idA3}`, // New DELETE route
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        Swal.fire(
                            'Dihapus!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page to refresh the table
                        });
                    },
                    error: function (xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        let errorMessage = 'Terjadi kesalahan saat menghapus data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire(
                            'Gagal!',
                            errorMessage,
                            'error'
                        );
                    }
                });
            }
        });
    });

});
