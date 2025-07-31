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

    // Simpan button reference
    const simpanButton = $('#simpan-button');

    // NEW: References for 50m inputs
    const sf50mContainer = $('#SF_50m_container');
    const sf50mEnableCheckbox = $('#SF_50m_enable_time_chkbx');
    const sf50mTimeFieldsDiv = $('#SF_50m_time_fields');
    const sf50mMmInput = $('#SF_50m_mm_txtbx');
    const sf50mSsInput = $('#SF_50m_ss_txtbx');
    const sf50mHsInput = $('#SF_50m_hs_txtbx');
    // NEW: References for 100m inputs
    const sf100mContainer = $('#SF_100m_container');
    const sf100mEnableCheckbox = $('#SF_100m_enable_time_chkbx');
    const sf100mTimeFieldsDiv = $('#SF_100m_time_fields');
    const sf100mMmInput = $('#SF_100m_mm_txtbx');
    const sf100mSsInput = $('#SF_100m_ss_txtbx');
    const sf100mHsInput = $('#SF_100m_hs_txtbx');
    // ADDED: References for 200m inputs
    const sf200mContainer = $('#SF_200m_container');
    const sf200mEnableCheckbox = $('#SF_200m_enable_time_chkbx');
    const sf200mTimeFieldsDiv = $('#SF_200m_time_fields');
    const sf200mMmInput = $('#SF_200m_mm_txtbx');
    const sf200mSsInput = $('#SF_200m_ss_txtbx');
    const sf200mHsInput = $('#SF_200m_hs_txtbx');
    // ADDED: References for 400m inputs
    const sf400mContainer = $('#SF_400m_container');
    const sf400mEnableCheckbox = $('#SF_400m_enable_time_chkbx');
    const sf400mTimeFieldsDiv = $('#SF_400m_time_fields');
    const sf400mMmInput = $('#SF_400m_mm_txtbx');
    const sf400mSsInput = $('#SF_400m_ss_txtbx');
    const sf400mHsInput = $('#SF_400m_hs_txtbx');
    // ADDED: References for 800m inputs
    const sf800mContainer = $('#SF_800m_container');
    const sf800mEnableCheckbox = $('#SF_800m_enable_time_chkbx');
    const sf800mTimeFieldsDiv = $('#SF_800m_time_fields');
    const sf800mMmInput = $('#SF_800m_mm_txtbx');
    const sf800mSsInput = $('#SF_800m_ss_txtbx');
    const sf800mHsInput = $('#SF_800m_hs_txtbx');
    // ADDED: References for 1500m inputs
    const sf1500mContainer = $('#SF_1500m_container');
    const sf1500mEnableCheckbox = $('#SF_1500m_enable_time_chkbx');
    const sf1500mTimeFieldsDiv = $('#SF_1500m_time_fields');
    const sf1500mMmInput = $('#SF_1500m_mm_txtbx');
    const sf1500mSsInput = $('#SF_1500m_ss_txtbx');
    const sf1500mHsInput = $('#SF_1500m_hs_txtbx');


    // NEW: References for 50m inputs
    const bf50mContainer = $('#BF_50m_container');
    const bf50mEnableCheckbox = $('#BF_50m_enable_time_chkbx');
    const bf50mTimeFieldsDiv = $('#BF_50m_time_fields');
    const bf50mMmInput = $('#BF_50m_mm_txtbx');
    const bf50mSsInput = $('#BF_50m_ss_txtbx');
    const bf50mHsInput = $('#BF_50m_hs_txtbx');
    // NEW: References for 100m inputs
    const bf100mContainer = $('#BF_100m_container');
    const bf100mEnableCheckbox = $('#BF_100m_enable_time_chkbx');
    const bf100mTimeFieldsDiv = $('#BF_100m_time_fields');
    const bf100mMmInput = $('#BF_100m_mm_txtbx');
    const bf100mSsInput = $('#BF_100m_ss_txtbx');
    const bf100mHsInput = $('#BF_100m_hs_txtbx');
    // ADDED: References for 200m inputs
    const bf200mContainer = $('#BF_200m_container');
    const bf200mEnableCheckbox = $('#BF_200m_enable_time_chkbx');
    const bf200mTimeFieldsDiv = $('#BF_200m_time_fields');
    const bf200mMmInput = $('#BF_200m_mm_txtbx');
    const bf200mSsInput = $('#BF_200m_ss_txtbx');
    const bf200mHsInput = $('#BF_200m_hs_txtbx');
    // ADDED: References for 400m inputs
    const bf400mContainer = $('#BF_400m_container');
    const bf400mEnableCheckbox = $('#BF_400m_enable_time_chkbx');
    const bf400mTimeFieldsDiv = $('#BF_400m_time_fields');
    const bf400mMmInput = $('#BF_400m_mm_txtbx');
    const bf400mSsInput = $('#BF_400m_ss_txtbx');
    const bf400mHsInput = $('#BF_400m_hs_txtbx');
    // ADDED: References for 800m inputs
    const bf800mContainer = $('#BF_800m_container');
    const bf800mEnableCheckbox = $('#BF_800m_enable_time_chkbx');
    const bf800mTimeFieldsDiv = $('#BF_800m_time_fields');
    const bf800mMmInput = $('#BF_800m_mm_txtbx');
    const bf800mSsInput = $('#BF_800m_ss_txtbx');
    const bf800mHsInput = $('#BF_800m_hs_txtbx');
    // ADDED: References for 1500m inputs
    const bf1500mContainer = $('#BF_1500m_container');
    const bf1500mEnableCheckbox = $('#BF_1500m_enable_time_chkbx');
    const bf1500mTimeFieldsDiv = $('#BF_1500m_time_fields');
    const bf1500mMmInput = $('#BF_1500m_mm_txtbx');
    const bf1500mSsInput = $('#BF_1500m_ss_txtbx');
    const bf1500mHsInput = $('#BF_1500m_hs_txtbx');

    // NEW: References for 50m inputs
    const ap50mContainer = $('#AP_50m_container');
    const ap50mEnableCheckbox = $('#AP_50m_enable_time_chkbx');
    const ap50mTimeFieldsDiv = $('#AP_50m_time_fields');
    const ap50mMmInput = $('#AP_50m_mm_txtbx');
    const ap50mSsInput = $('#AP_50m_ss_txtbx');
    const ap50mHsInput = $('#AP_50m_hs_txtbx');

    // Add these logs immediately after defining the variables
    console.log('JS Initial Check: sf50mContainer found?', sf50mContainer.length > 0 ? 'Yes' : 'No', sf50mContainer);
    console.log('JS Initial Check: sf100mContainer found?', sf100mContainer.length > 0 ? 'Yes' : 'No', sf100mContainer);
    // ADDED: Logs for 200m and 400m containers
    console.log('JS Initial Check: sf200mContainer found?', sf200mContainer.length > 0 ? 'Yes' : 'No', sf200mContainer);
    console.log('JS Initial Check: sf400mContainer found?', sf400mContainer.length > 0 ? 'Yes' : 'No', sf400mContainer);


    // Global variable expected from Blade: window.atletDetails
    // This object will contain all athlete details, keyed by their IDATLET.
    const allAtletDetails = window.atletDetails || {};
    console.log('Loaded allAtletDetails:', allAtletDetails);
    const currentUserEmail = window.currentUserEmail || ''; // Get current user's email

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

    // Setup all time input groups
    const resetSf50mGroupContents = setupTimeInputGroup(sf50mContainer, sf50mEnableCheckbox, sf50mTimeFieldsDiv, sf50mMmInput, sf50mSsInput, sf50mHsInput);
    const resetSf100mGroupContents = setupTimeInputGroup(sf100mContainer, sf100mEnableCheckbox, sf100mTimeFieldsDiv, sf100mMmInput, sf100mSsInput, sf100mHsInput);
    const resetSf200mGroupContents = setupTimeInputGroup(sf200mContainer, sf200mEnableCheckbox, sf200mTimeFieldsDiv, sf200mMmInput, sf200mSsInput, sf200mHsInput);
    const resetSf400mGroupContents = setupTimeInputGroup(sf400mContainer, sf400mEnableCheckbox, sf400mTimeFieldsDiv, sf400mMmInput, sf400mSsInput, sf400mHsInput);
    const resetSf800mGroupContents = setupTimeInputGroup(sf800mContainer, sf800mEnableCheckbox, sf800mTimeFieldsDiv, sf800mMmInput, sf800mSsInput, sf800mHsInput);
    const resetSf1500mGroupContents = setupTimeInputGroup(sf1500mContainer, sf1500mEnableCheckbox, sf1500mTimeFieldsDiv, sf1500mMmInput, sf1500mSsInput, sf1500mHsInput);

    // Setup all time input groups
    const resetBf50mGroupContents = setupTimeInputGroup(bf50mContainer, bf50mEnableCheckbox, bf50mTimeFieldsDiv, bf50mMmInput, bf50mSsInput, bf50mHsInput);
    const resetBf100mGroupContents = setupTimeInputGroup(bf100mContainer, bf100mEnableCheckbox, bf100mTimeFieldsDiv, bf100mMmInput, bf100mSsInput, bf100mHsInput);
    const resetBf200mGroupContents = setupTimeInputGroup(bf200mContainer, bf200mEnableCheckbox, bf200mTimeFieldsDiv, bf200mMmInput, bf200mSsInput, bf200mHsInput);
    const resetBf400mGroupContents = setupTimeInputGroup(bf400mContainer, bf400mEnableCheckbox, bf400mTimeFieldsDiv, bf400mMmInput, bf400mSsInput, bf400mHsInput);
    const resetBf800mGroupContents = setupTimeInputGroup(bf800mContainer, bf800mEnableCheckbox, bf800mTimeFieldsDiv, bf800mMmInput, bf800mSsInput, bf800mHsInput);
    const resetBf1500mGroupContents = setupTimeInputGroup(bf1500mContainer, bf1500mEnableCheckbox, bf1500mTimeFieldsDiv, bf1500mMmInput, bf1500mSsInput, bf1500mHsInput);

    // Setup all time input groups
    const resetAp50mGroupContents = setupTimeInputGroup(ap50mContainer, ap50mEnableCheckbox, ap50mTimeFieldsDiv, ap50mMmInput, ap50mSsInput, ap50mHsInput);

    // Event listener for the "Pilih" button
    pilihAtletButton.on('click', function () {
        // Get the IDATLET from the selected option
        const selectedIdAtlet = namaAtletSelect.val();

        if (!selectedIdAtlet) {
            alert('Silahkan pilih atlet dari daftar terlebih dahulu.');
            sf50mContainer.addClass('hidden'); // Explicitly hide the container
            resetSf50mGroupContents(); // Reset inner checkbox/fields
            sf100mContainer.addClass('hidden'); // Explicitly hide the container
            resetSf100mGroupContents(); // Reset inner checkbox/fields
            sf200mContainer.addClass('hidden');
            resetSf200mGroupContents();
            sf400mContainer.addClass('hidden');
            resetSf400mGroupContents();
            sf800mContainer.addClass('hidden');
            resetSf800mGroupContents();
            sf1500mContainer.addClass('hidden');
            resetSf1500mGroupContents();


            bf50mContainer.addClass('hidden'); // Explicitly hide the container
            resetBf50mGroupContents(); // Reset inner checkbox/fields
            bf100mContainer.addClass('hidden'); // Explicitly hide the container
            resetBf100mGroupContents(); // Reset inner checkbox/fields
            bf200mContainer.addClass('hidden');
            resetBf200mGroupContents();
            bf400mContainer.addClass('hidden');
            resetBf400mGroupContents();
            bf800mContainer.addClass('hidden');
            resetBf800mGroupContents();
            bf1500mContainer.addClass('hidden');
            resetBf1500mGroupContents();

            ap50mContainer.addClass('hidden'); // Explicitly hide the container
            resetAp50mGroupContents(); // Reset inner checkbox/fields

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

            // NEW: Show the containers for all surface distances
            sf50mContainer.removeClass('hidden');
            resetSf50mGroupContents(); // Reset to initial state (checkbox unchecked, fields hidden)
            sf100mContainer.removeClass('hidden');
            resetSf100mGroupContents(); // Reset to initial state (checkbox unchecked, fields hidden)
            // ADDED: Show and reset 200m and 400m containers
            sf200mContainer.removeClass('hidden');
            resetSf200mGroupContents();
            sf400mContainer.removeClass('hidden');
            resetSf400mGroupContents();
            // ADDED: Show and reset 800m and 1500m containers
            sf800mContainer.removeClass('hidden');
            resetSf800mGroupContents();
            sf1500mContainer.removeClass('hidden');
            resetSf1500mGroupContents();


            // NEW: Show the containers for all surface distances
            bf50mContainer.removeClass('hidden');
            resetBf50mGroupContents(); // Reset to initial state (checkbox unchecked, fields hidden)
            bf100mContainer.removeClass('hidden');
            resetBf100mGroupContents(); // Reset to initial state (checkbox unchecked, fields hidden)
            // ADDED: Show and reset 200m and 400m containers
            bf200mContainer.removeClass('hidden');
            resetBf200mGroupContents();
            bf400mContainer.removeClass('hidden');
            resetBf400mGroupContents();
            // ADDED: Show and reset 800m and 1500m containers
            bf800mContainer.removeClass('hidden');
            resetBf800mGroupContents();
            bf1500mContainer.removeClass('hidden');
            resetBf1500mGroupContents();

            // NEW: Show the containers for all surface distances
            ap50mContainer.removeClass('hidden');
            resetAp50mGroupContents(); // Reset to initial state (checkbox unchecked, fields hidden)

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

            sf50mContainer.addClass('hidden'); // Explicitly hide the container
            resetSf50mGroupContents(); // Reset inner checkbox/fields
            sf100mContainer.addClass('hidden'); // Explicitly hide the container
            resetSf100mGroupContents(); // Reset inner checkbox/fields
            // ADDED: Hide and reset 200m and 400m containers
            sf200mContainer.addClass('hidden');
            resetSf200mGroupContents();
            sf400mContainer.addClass('hidden');
            resetSf400mGroupContents();
            // ADDED: Hide and reset 800m and 1500m containers
            sf800mContainer.addClass('hidden');
            resetSf800mGroupContents();
            sf1500mContainer.addClass('hidden');
            resetSf1500mGroupContents();


            bf50mContainer.addClass('hidden'); // Explicitly hide the container
            resetBf50mGroupContents(); // Reset inner checkbox/fields
            bf100mContainer.addClass('hidden'); // Explicitly hide the container
            resetBf100mGroupContents(); // Reset inner checkbox/fields
            // ADDED: Hide and reset 200m and 400m containers
            bf200mContainer.addClass('hidden');
            resetBf200mGroupContents();
            bf400mContainer.addClass('hidden');
            resetBf400mGroupContents();
            // ADDED: Hide and reset 800m and 1500m containers
            bf800mContainer.addClass('hidden');
            resetBf800mGroupContents();
            bf1500mContainer.addClass('hidden');
            resetBf1500mGroupContents();

            ap50mContainer.addClass('hidden'); // Explicitly hide the container
            resetAp50mGroupContents(); // Reset inner checkbox/fields
        }
    });

    // Event listener for the "Simpan" button
    simpanButton.on('click', function (e) {
        e.preventDefault(); // Prevent default form submission

        const selectedIdAtlet = namaAtletSelect.val();
        if (!selectedIdAtlet) {
            alert('Silakan pilih atlet terlebih dahulu sebelum menyimpan.');
            return;
        }

        const athlete = allAtletDetails[selectedIdAtlet];
        if (!athlete) {
            alert('Data atlet tidak ditemukan. Mohon pilih atlet yang valid.');
            return;
        }

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
            sp: athlete.SP,
            tgl_lahir: athlete.TGLLAHIR,
            nomor: 'Perorangan', // Fixed value as requested
            email: currentUserEmail, // User's email from Blade
        };

        // Process SF 50m time data
        const sf50mTimeData = processTimeInputGroupData(
            '50m', sf50mEnableCheckbox, sf50mMmInput, sf50mSsInput, sf50mHsInput
        );

        if (!sf50mTimeData.isValid) {
            alert(sf50mTimeData.errorMessage);
            return;
        }

        // Add SF 50m data to formData
        formData.MON50MM = sf50mTimeData.mm;
        formData.MON50SS = sf50mTimeData.ss;
        formData.MON50HS = sf50mTimeData.hs;

        // You would repeat this for SF 100m, SF 200m, SF 400m, SF 800m, SF 1500m etc.
        // For example:
        // const sf100mTimeData = processTimeInputGroupData(
        //     '100m', sf100mEnableCheckbox, sf100mMmInput, sf100mSsInput, sf100mHsInput
        // );
        // if (!sf100mTimeData.isValid) { alert(sf100mTimeData.errorMessage); return; }
        // formData.MON100MM = sf100mTimeData.mm;
        // formData.MON100SS = sf100mTimeData.ss;
        // formData.MON100HS = sf100mTimeData.hs;


        console.log('Data to be sent:', formData); // For debugging

        // Send data via AJAX
        $.ajax({
            url: $('#form-a3-perorangan').attr('action'), // Get URL from form action
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                    // Optionally, clear the form or update the "Daftar Entri" table
                    // For now, just a simple alert.
                } else {
                    alert('Gagal menyimpan data: ' + response.message);
                }
            },
            error: function (xhr) {
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
    });
});
