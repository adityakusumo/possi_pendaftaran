// resources/js/form_a1_namaatlet.js
// Only JavaScript code here

document.addEventListener('DOMContentLoaded', function () {
    // Main Form Submission Confirmation
    const mainForm = document.getElementById('main-form-a1-namaatlet');
    if (mainForm) {
        mainForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Stop default submission

            // Basic check: Ensure a NIAS athlete has been selected for saving
            // This is crucial: if you select from the lower table, nonias will be cleared.
            // Only allow saving if selected_nias_nonias has a value from the top table.
            const selectedNiasNonias = document.getElementById('selected_nias_nonias').value;
            if (!selectedNiasNonias) {
                alert('Silakan pilih atlet dari daftar NIAS di atas untuk menambahkan/mengubah data, atau pastikan semua data manual terisi.');
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
    window.confirmDeleteAtlet = function () {
        // This should get the NONIAS from the hidden input dedicated to deletion,
        // which is set when an item from the lower 'atletListTableBody' is clicked.
        const noniasToDelete = document.getElementById('nonias_to_delete_hidden').value;

        if (!noniasToDelete) {
            alert('Pilih atlet dari daftar atlet yang sudah ditambahkan (tabel bawah) untuk dihapus.');
            return; // Prevent deletion if no NONIAS is selected for deletion
        }

        if (confirm('Apakah Anda yakin ingin menghapus atlet ini? Ini akan menghapus data atlet dengan NONIAS: ' + noniasToDelete)) {
            const deleteForm = document.getElementById('delete-atlet-form');
            if (deleteForm) {
                // The nonias_to_delete_hidden should already be populated by the lower table click handler
                // No need to set it here again, just ensure it's there.
                deleteForm.submit(); // Submit the delete form
            } else {
                console.error('Delete form not found!');
            }
        }
    };
});


// jQuery Dependent Scripts (inside $(document).ready())
$(document).ready(function () {
    console.log('Document ready. Initializing Form A1 Nama Atlet scripts.');

    const niasTableBody = $('#nias-table-body'); // jQuery object for NIAS search results table
    const atletListTableBody = $('#atlet-list-table-body'); // NEW: Get the tbody of your lower table
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
    const selectedNiasExpiredHidden = $('#selected_nias_expired');
    const selectedNiasExp1009Hidden = $('#selected_nias_exp1009'); // Make sure this is present and correct if needed

    const searchNiasButton = $('#search-nias-button');
    const niasInput = $('#nias_input');

    // Ensure mstKuData is available (passed from controller via Blade's @json)
    // Access it from window.mstKuData
    const mstKuData = window.mstKuData || []; // Fallback to empty array if not defined globally
    console.log("MstKU Data for comparison:", mstKuData);

    // Function to handle row selection (applies to both tables)
    function handleRowSelection(clickedRow) {
        if (selectedRow) {
            selectedRow.removeClass('bg-blue-100 dark:bg-blue-900 ring-2 ring-blue-500');
        }

        clickedRow.addClass('bg-blue-100 dark:bg-blue-900 ring-2 ring-blue-500');
        selectedRow = clickedRow;
    }

    // Function to calculate KU based on birth date (moved to global scope if needed by other parts, otherwise keep here)
    function calculateKu(birthDate) {
        if (!birthDate) return '';
        const today = new Date();
        const dob = new Date(birthDate);
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        if (age >= 17) return 'E';
        else if (age >= 15) return 'D';
        else if (age >= 13) return 'C';
        else if (age >= 11) return 'B';
        else if (age >= 9) return 'A';
        else if (age >= 7) return 'P';
        return ''; // For ages below 7
    }


    // Function to populate main form fields from athlete details
    function populateFormFields(athleteDetails) {
        namaClubInput.val(athleteDetails.NAMACLUB || '');
        jenisKotaKabInput.val(athleteDetails.JENIS || '');
        namaKotaKabInput.val(athleteDetails.NAMAKOTA || '');
        propinsiInput.val(athleteDetails.NAMAPROP || '');
        negaraInput.val(athleteDetails.NEGARA || 'INDONESIA');
        namaAtletInput.val(athleteDetails.NAMA || '');

        selectedNiasNoniasHidden.val(athleteDetails.NONIAS || '');
        selectedNiasExp1009Hidden.val(athleteDetails.EXP1009 || ''); // Populate this if you fetch it
        selectedNiasExpiredHidden.val(athleteDetails.EXPIRED || ''); // Populate this if you fetch it

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

        // KU logic
        if (athleteDetails.KU) { // Prefer KU from NIAS data if available
            kuDSelect.val(athleteDetails.KU);
        } else if (athleteBirthDate && !isNaN(athleteBirthDate.getTime())) { // Calculate if birth date is valid
            kuDSelect.val(calculateKu(athleteDetails.TGLLAHIR));
        } else {
            kuDSelect.val(''); // Clear if no KU and no valid birth date
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
            genderWanitaRadio.prop('checked', true); // Default to Wanita if gender is not provided
        }

        sparingPartnerYesRadio.prop('checked', false);
        sparingPartnerNoRadio.prop('checked', false);

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
    }


    // Function to render a single table row
    function renderNiasTableRow(athlete, index, currentPage, perPage) {
        const rowNumber = (currentPage - 1) * perPage + index + 1;
        const detailsJson = JSON.stringify(athlete);

        return `
            <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 selectable-row"
                data-athlete-id="${athlete.ID || rowNumber}"
                data-athlete-details='${detailsJson}'>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                    ${rowNumber}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">${athlete.NAMACLUB || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">${athlete.JENIS || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">${athlete.NAMAKOTA || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">${athlete.NAMAPROP || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">${athlete.NAMA || ''}</td>
            </tr>
        `;
    }

    // Function to fetch and render NIAS search results
    function fetchAndRenderNiasResults(searchQuery = '', page = 1) {
        $.ajax({
            // *** FIX: Use window.appConfig.routes.niasSearch here ***
            url: window.appConfig.routes.niasSearch,
            method: 'GET',
            data: { query: searchQuery, page: page },
            success: function (response) {
                // Clear existing rows
                niasTableBody.empty();
                if (response.data && response.data.length > 0) {
                    let rowsHtml = '';
                    $.each(response.data, function (index, athlete) {
                        rowsHtml += renderNiasTableRow(athlete, index, response.current_page, response.per_page);
                    });
                    niasTableBody.html(rowsHtml);

                    // Re-render pagination links
                    const paginationContainer = niasTableBody.closest('.overflow-x-auto').next('.mt-4'); // Get the pagination div
                    paginationContainer.empty();
                    if (response.last_page > 1) {
                        let paginationLinksHtml = '<nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">';
                        // Previous Button
                        if (response.prev_page_url) {
                            paginationLinksHtml += `<a href="#" class="pagination-link relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700" data-page="${response.current_page - 1}">&laquo; Previous</a>`;
                        } else {
                            paginationLinksHtml += `<span class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500">Previous</span>`;
                        }

                        // Next Button
                        if (response.next_page_url) {
                            paginationLinksHtml += `<a href="#" class="pagination-link relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 ml-3 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700" data-page="${response.current_page + 1}">Next &raquo;</a>`;
                        } else {
                            paginationLinksHtml += `<span class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 ml-3 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500">Next</span>`;
                        }
                        paginationLinksHtml += '</nav>';
                        paginationContainer.html(paginationLinksHtml);
                    }
                } else {
                    niasTableBody.html(`<tr><td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        ${window.appConfig.translations.noNiasData}
                    </td></tr>`);
                    // Clear pagination if no results
                    niasTableBody.closest('.overflow-x-auto').next('.mt-4').empty();
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX search failed:', status, error, xhr.responseText);
                alert('Terjadi kesalahan saat mencari data NIAS.');
                niasTableBody.html(`<tr><td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-red-500 dark:text-red-400 text-center">
                    ${window.appConfig.translations.failedToLoadNias}
                </td></tr>`);
            }
        });
    }

    // --- Initial Load of NIAS data (if any) ---
    // If your controller already passes `niasList` on initial page load,
    // this will be handled by Blade. If you want initial load via AJAX too,
    // call fetchAndRenderNiasResults() here without a query.
    // fetchAndRenderNiasResults(); // Uncomment if you want AJAX for initial load

    // --- Event Listener for NIAS search results table row clicks (TOP TABLE) ---
    niasTableBody.on('click', '.selectable-row', function () {
        const clickedRow = $(this);
        handleRowSelection(clickedRow); // Highlight the row

        const athleteDetails = clickedRow.data('athlete-details');
        console.log('Parsed athlete details object from NIAS:', athleteDetails);

        populateFormFields(athleteDetails); // Populate the main form for saving/editing

        // Clear the nonias_to_delete_hidden input, as we are now working with the main form for saving
        $('#nonias_to_delete_hidden').val('');
    });

    // Event listener for pagination links
    $(document).on('click', '.pagination-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        const currentSearchQuery = niasInput.val(); // Use current search query
        fetchAndRenderNiasResults(currentSearchQuery, page);
    });

    searchNiasButton.on('click', function () {
        const searchValue = niasInput.val();
        console.log('Searching for NIAS with input:', searchValue);
        fetchAndRenderNiasResults(searchValue, 1); // Start search from page 1
    });

    // Optional: Search on Enter key in the input field
    niasInput.on('keypress', function (e) {
        if (e.which === 13) { // Enter key
            e.preventDefault(); // Prevent form submission if input is part of a form
            searchNiasButton.click(); // Trigger the search button click
        }
    });

    // NEW: Event Listener for LOWER TABLE (Display of currently added athletes) row clicks
    atletListTableBody.on('click', '.atlet-row-selectable', function () {
        const clickedRow = $(this);
        handleRowSelection(clickedRow); // Highlight the clicked row

        const noniasOfSelectedAthlete = clickedRow.data('nonias');
        console.log('Selected athlete from lower table (NONIAS):', noniasOfSelectedAthlete);

        // Set the NONIAS into the hidden input that the delete button relies on
        $('#nonias_to_delete_hidden').val(noniasOfSelectedAthlete);

        // CRITICAL: Clear the main form's hidden NONIAS, as this selection is for DELETION
        // AND clear all other main form fields, to prevent accidental 'Simpan' with mixed data
        // or submitting an incomplete record. This prepares the form for a new entry.
        selectedNiasNoniasHidden.val('');
        selectedNiasExp1009Hidden.val('');
        selectedNiasExpiredHidden.val('');
        namaClubInput.val('');
        jenisKotaKabInput.val('');
        namaKotaKabInput.val('');
        propinsiInput.val('');
        negaraInput.val('INDONESIA'); // Default for new entry
        namaAtletInput.val('');
        birthDaySelect.val('');
        birthMonthSelect.val('');
        birthYearSelect.val('');
        kuDSelect.val(''); // Ensure it's kuDSelect as per your variables
        genderPriaRadio.prop('checked', false);
        genderWanitaRadio.prop('checked', true); // Default to Wanita
        sparingPartnerYesRadio.prop('checked', false);
        sparingPartnerNoRadio.prop('checked', true); // Default to No SP
        niasNumberDisplay.val('');
        exp1009Input.val('');

        detailsDisplay.text('Athlete selected from lower list. Main form cleared for a NEW entry or to facilitate DELETION of the selected athlete.');
    });
});
