// resources/js/form_a1_namaatlet.js
// Only JavaScript code here

document.addEventListener('DOMContentLoaded', function() {
    // Main Form Submission Confirmation
    const mainForm = document.getElementById('main-form-a1-namaatlet');
    if (mainForm) {
        mainForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Stop default submission

            // Basic check: Ensure a NIAS athlete has been selected
            if (!document.getElementById('selected_nias_nonias').value) {
                alert('Silakan pilih atlet dari daftar NIAS terlebih dahulu atau masukkan data secara manual.');
                return; // Prevent submission
            }

            if (confirm('Anda yakin ingin menyimpan data atlet?')) {
                event.target.submit(); // If confirmed, submit the form
            } else {
                console.log('Data saving cancelled by user.');
            }
        });
    }

    // Delete Atlet Confirmation (for the Hapus button)
    window.confirmDeleteAtlet = function() {
        const noniasToDelete = document.getElementById('selected_nias_nonias').value;

        if (!noniasToDelete) {
            alert('Pilih atlet yang ingin dihapus terlebih dahulu dari daftar NIAS atau masukkan NIAS atlet yang sudah terdaftar.');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus atlet ini? Ini akan menghapus data atlet dengan NIAS: ' + noniasToDelete)) {
            const deleteForm = document.getElementById('delete-atlet-form');
            if (deleteForm) {
                document.getElementById('nonias_to_deletenias').value = noniasToDelete;
                deleteForm.submit();
            } else {
                console.error('Delete form not found!');
            }
        }
    };
});


// jQuery Dependent Scripts (inside $(document).ready())
$(document).ready(function() {
    console.log('Document ready. Initializing Form A1 Nama Atlet scripts.');

    const niasTableBody = $('#nias-table-body'); // jQuery object for NIAS search results table
    const detailsDisplay = $('#selected-athlete-details'); // jQuery object for debug display
    let selectedRow = null;

    // Form elements for "DATA ATLET" section
    const namaClubInput = $('#nama_club_new');
    const jenisKotaKabInput = $('#kota_kab_new');
    const namaKotaKabInput = $('#nama_kota_kab_new');
    const propinsiInput = $('#propinsi_new');
    const negaraInput = $('#negara_new');
    const namaAtletInput = $('#nama_atlet_new');

    const birthDaySelect = $('#birth_day');
    const birthMonthSelect = $('#birth_month');
    const birthYearSelect = $('#birth_year');

    const kuDSelect = $('#ku_d'); // This is the KU select

    const genderPriaRadio = $('#gender_pria');
    const genderWanitaRadio = $('#gender_wanita');

    const sparingPartnerYesRadio = $('#sparing_partner_yes');
    const sparingPartnerNoRadio = $('#sparing_partner_no');
    const niasNumberDisplay = $('#nias_number');

    const exp1009Input = $('#exp1009_input');

    // Hidden inputs that will hold the values for submission
    const selectedNiasNoniasHidden = $('#selected_nias_nonias');
    const selectedNiasExp1009Hidden = $('#selected_nias_exp1009');

    // Ensure mstKuData is available (passed from controller via Blade's @json)
    // This line needs to be handled carefully if you are extracting it to a separate JS file.
    // Option 1: Pass it as a global variable.
    // Option 2: Fetch it via AJAX in the JS file.
    // For now, let's assume it's available via a global variable set in the Blade file.
    // This will require you to set `window.mstKuData = @json($mstKuData ?? []);` in your Blade file.
    const mstKuData = window.mstKuData || []; // Fallback to empty array if not defined globally
    console.log("MstKU Data for comparison:", mstKuData);

    // --- Event Listener for NIAS table row clicks ---
    niasTableBody.on('click', '.selectable-row', function() {
        const clickedRow = $(this);

        if (selectedRow) {
            selectedRow.removeClass('bg-blue-100 dark:bg-blue-900 ring-2 ring-blue-500');
        }

        clickedRow.addClass('bg-blue-100 dark:bg-blue-900 ring-2 ring-blue-500');
        selectedRow = clickedRow;

        const athleteDetails = clickedRow.data('athlete-details');
        console.log('Parsed athlete details object:', athleteDetails);

        namaClubInput.val(athleteDetails.NAMACLUB || '');
        jenisKotaKabInput.val(athleteDetails.KDJENIS || athleteDetails.JENIS || '');
        namaKotaKabInput.val(athleteDetails.NAMAKOTA || '');
        propinsiInput.val(athleteDetails.NAMAPROP || '');
        negaraInput.val(athleteDetails.NEGARA || 'INDONESIA');
        namaAtletInput.val(athleteDetails.NAMA || '');

        selectedNiasNoniasHidden.val(athleteDetails.NONIAS || '');
        selectedNiasExp1009Hidden.val(athleteDetails.EXP1009 || '');

        let athleteBirthDate = null;
        if (athleteDetails.TGLLAHIR) {
            try {
                const dob = new Date(athleteDetails.TGLLAHIR);
                dob.setHours(0, 0, 0, 0);
                athleteBirthDate = dob;

                if (!isNaN(dob.getDate())) birthDaySelect.val(String(dob.getDate()).padStart(2, '0'));
                if (!isNaN(dob.getMonth())) birthMonthSelect.val(String(dob.getMonth() + 1).padStart(2, '0'));
                if (!isNaN(dob.getFullYear())) birthYearSelect.val(dob.getFullYear());
            } catch (e) {
                console.error('Error parsing TGLLAHIR:', e);
                birthDaySelect.val('');
                birthMonthSelect.val('');
                birthYearSelect.val('');
            }
        } else {
            birthDaySelect.val('');
            birthMonthSelect.val('');
            birthYearSelect.val('');
        }

        if (athleteBirthDate && !isNaN(athleteBirthDate.getTime()) && mstKuData.length > 0) {
            let kuFound = false;
            kuDSelect.val('');
            const currentYear = new Date().getFullYear();
            for (let i = 0; i < mstKuData.length; i++) {
                const kuEntry = mstKuData[i];
                const lahirMulai = new Date(kuEntry.LAHIRMULAI);
                const lahirSampai = new Date(kuEntry.LAHIRSAMPAI);

                if (!isNaN(lahirMulai.getTime()) && !isNaN(lahirSampai.getTime())) {
                    lahirMulai.setHours(0, 0, 0, 0);
                    lahirSampai.setHours(23, 59, 59, 999);

                    if (athleteBirthDate >= lahirMulai && athleteBirthDate <= lahirSampai) {
                        kuDSelect.val(kuEntry.KU);
                        kuFound = true;
                        break;
                    }
                } else {
                    console.warn('Invalid LAHIRMULAI or LAHIRSAMPAI date for KU:', kuEntry.KU, kuEntry.LAHIRMULAI, kuEntry.LAHIRSAMPAI);
                }
            }
            if (!kuFound) {
                console.log("No matching KU found for athlete's birth date:", athleteDetails.TGLLAHIR);
                kuDSelect.val('');
            }
        } else {
            kuDSelect.val('');
        }

        genderPriaRadio.prop('checked', false);
        genderWanitaRadio.prop('checked', false);

        if (athleteDetails.GENDER) {
            const genderValue = athleteDetails.GENDER.toUpperCase();
            if (genderValue === 'PA') {
                genderPriaRadio.prop('checked', true);
            } else if (genderValue === 'PI') {
                genderWanitaRadio.prop('checked', true);
            }
        } else {
            genderWanitaRadio.prop('checked', true);
        }

        if (athleteDetails.SP && (athleteDetails.SP.toUpperCase() === 'Y' || athleteDetails.SP.toUpperCase() === 'SP')) {
            sparingPartnerYesRadio.prop('checked', true);
            sparingPartnerNoRadio.prop('checked', false);
        } else {
            sparingPartnerYesRadio.prop('checked', false);
            sparingPartnerNoRadio.prop('checked', true);
        }

        niasNumberDisplay.val(athleteDetails.NONIAS || '');
        exp1009Input.val(athleteDetails.EXP1009 || '');

        detailsDisplay.text(JSON.stringify(athleteDetails, null, 2));
    });

    const searchNiasButton = $('#search-nias-button');
    const niasInput = $('#nias_input');

    searchNiasButton.on('click', function() {
        const searchValue = niasInput.val();
        console.log('Searching for NIAS with input:', searchValue);
        alert('Search functionality for NIAS list is currently displaying pre-loaded data. For live search, you need a backend route that handles the search query.');
    });
});
