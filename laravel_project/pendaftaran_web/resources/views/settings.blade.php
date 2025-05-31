<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        General Account Settings
                    </h3>

                    {{-- Search Form --}}
                    <div class="mb-4 flex items-center">
                        <form action="{{ route('settings') }}" method="GET" class="flex-grow flex items-center">
                            <label for="search-input" class="sr-only">Cari Pengguna</label>
                            <input type="text" name="cari" id="search-input"
                                placeholder="Cari Pengguna (Nama atau Email)..."
                                value="{{ request('cari') }}"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full max-w-md">
                            <button type="submit"
                                class="ms-3 px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('CARI') }}
                            </button>
                            @if(request('cari'))
                                <a href="{{ route('settings') }}" class="ms-3 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                    {{ __('Clear Search') }}
                                </a>
                            @endif
                        </form>
                    </div>                    

                    {{-- User Management Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Club
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Action
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date Created
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($users as $key => $user)
                                    <tr data-user-id="{{ $user->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $users->firstItem() + $loop->index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->email }}
                                        </td>
                                        {{-- CLUB DROPDOWN --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <select name="club_IDCLUB" {{-- Changed name to club_IDCLUB --}}
                                                class="form-select club-select p-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm
                                                bg-white dark:bg-gray-700 disabled:bg-gray-200 disabled:dark:bg-gray-600
                                                dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                disabled>
                                                <option value="" {{ is_null($user->IDCLUB) ? 'selected' : '' }}>Select Club</option> {{-- Use IDCLUB for selection --}}
                                                @foreach ($clubs as $club)
                                                    <option value="{{ $club->IDCLUB }}" {{-- Value is IDCLUB --}}
                                                        {{ $user->IDCLUB === $club->IDCLUB ? 'selected' : '' }}> {{-- Check IDCLUB for selection --}}
                                                        {{ $club->NAMACLUB }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        {{-- END CLUB DROPDOWN --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{-- Use a form for updates --}}
                                            <form id="user-update-form-{{ $user->id }}"
                                                action="{{ route('settings.update', $user) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role"
                                                    class="form-select role-select p-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm
                                                    bg-white dark:bg-gray-700 disabled:bg-gray-200 disabled:dark:bg-gray-600
                                                    dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    disabled>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="hidden update-role-submit-btn"></button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button type="button"
                                                class="edit-button text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-md px-2 py-1">
                                                Edit
                                            </button>
                                            <form action="{{ route('settings.destroy-user', $user) }}" method="POST"
                                                class="inline-block delete-user-form ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="delete-button text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-md px-2 py-1">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->created_at->format('Y-m-d') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>                     

                    <p class="mt-4 text-gray-700 dark:text-gray-300">You can add forms, options, and other settings
                        here.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="club-validation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl text-center">
            <p class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Nama Club belum dipilih!</p>
            <button id="club-validation-ok-btn" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                OK
            </button>
        </div>
    </div>    

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // --- NEW: Force initial state on every page load ---
                document.querySelectorAll('.role-select').forEach(select => {
                    select.disabled = true; // Ensure all role dropdowns are disabled
                    // Also ensure their color classes are for disabled state if necessary
                    // (though Tailwind's disabled: variant usually handles this visually)
                    select.classList.remove('bg-white', 'dark:bg-gray-700');
                    select.classList.add('disabled:bg-gray-200', 'disabled:dark:bg-gray-600'); // Ensure these are active if disabled
                });
                document.querySelectorAll('.club-select').forEach(select => {
                    select.disabled = true; // Ensure all club dropdowns are disabled
                    // Also ensure their color classes are for disabled state if necessary
                    select.classList.remove('bg-white', 'dark:bg-gray-700');
                    select.classList.add('disabled:bg-gray-200', 'disabled:dark:bg-gray-600');
                });
                document.querySelectorAll('.edit-button').forEach(button => {
                    button.textContent = 'Edit'; // Ensure button text is "Edit"
                    // Reset button colors to default "Edit" state colors
                    button.classList.remove('text-green-600', 'hover:text-green-900', 'dark:text-green-400', 'dark:hover:text-green-600');
                    button.classList.add('text-indigo-600', 'hover:text-indigo-900', 'dark:text-indigo-400', 'dark:hover:text-indigo-600');
                });
                document.querySelectorAll('tr').forEach(row => {
                    row.classList.remove('editing-row'); // Remove any editing row classes
                });
                // --- END NEW ---
                
                const editButtons = document.querySelectorAll('.edit-button');
                const deleteButtons = document.querySelectorAll('.delete-button');

                // Get references to the modal elements
                const clubValidationModal = document.getElementById('club-validation-modal');
                const clubValidationOkBtn = document.getElementById('club-validation-ok-btn');

                // Function to show the modal
                function showClubValidationModal() {
                    clubValidationModal.classList.remove('hidden');
                }
                // Function to hide the modal
                function hideClubValidationModal() {
                    clubValidationModal.classList.add('hidden');
                }

	            // Event listener for the modal's OK button
	            clubValidationOkBtn.addEventListener('click', hideClubValidationModal); 

                editButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const row = this.closest('tr');
                        const userId = row.dataset.userId;

                        const roleSelectElement = row.querySelector('.role-select');
                        const clubSelectElement = row.querySelector('.club-select');
                        const submitButton = row.querySelector('.update-role-submit-btn');
                        const form = row.querySelector(`#user-update-form-${userId}`); // Get the specific form for this user

                        if (!roleSelectElement || !clubSelectElement || !form) {
                            console.error('Error: Could not find required elements for this row.');
                            return;
                        }

                        if (roleSelectElement.disabled) {
                            // Current state: "Edit" button, dropdowns are disabled.
                            // Action: Enable dropdowns, change button to "Save".
                            roleSelectElement.disabled = false;
                            clubSelectElement.disabled = false;
                            this.textContent = 'Save';
                            this.classList.remove('text-indigo-600', 'hover:text-indigo-900', 'dark:text-indigo-400', 'dark:hover:text-indigo-600');
                            this.classList.add('text-green-600', 'hover:text-green-900', 'dark:text-green-400', 'dark:hover:text-green-600');
                            row.classList.add('editing-row');
                            console.log('Role and Club dropdowns enabled for editing.');
                        } else {
                            // Current state: "Save" button, dropdowns are enabled.
                            // Action: Capture values and submit form, then disable dropdowns.

                            // 1. Get the selected club value
                            const selectedClubId = clubSelectElement.value;

                            // *** NEW VALIDATION LOGIC HERE ***
                            if (selectedClubId === '') { // Check if the "Select Club" option (value="") is chosen
                                showClubValidationModal();
                                
                                // Revert UI to initial "Edit" state immediately, as submission was blocked
                                roleSelectElement.disabled = true;
                                clubSelectElement.disabled = true;
                                this.textContent = 'Edit';
                                this.classList.remove('text-green-600', 'hover:text-green-900', 'dark:text-green-400', 'dark:hover:text-green-600');
                                this.classList.add('text-indigo-600', 'hover:text-indigo-900', 'dark:text-indigo-400', 'dark:hover:text-indigo-600');
                                row.classList.remove('editing-row');

                                console.log('Validation failed. UI reverted to disabled state.');                                
                                return; // STOP here, do not proceed with form submission
                            }
                            // *** END NEW VALIDATION LOGIC ***                            

                            // 2. Create a hidden input for the club ID
                            let hiddenClubInput = form.querySelector('input[name="club_IDCLUB"]');
                            if (!hiddenClubInput) {
                                hiddenClubInput = document.createElement('input');
                                hiddenClubInput.type = 'hidden';
                                hiddenClubInput.name = 'club_IDCLUB';
                                form.appendChild(hiddenClubInput); // Append to the form
                            }
                            hiddenClubInput.value = selectedClubId; // Set its value

                            // No longer need to move the select element, as the hidden input carries the data
                            // clubSelectElement is just for display now, its value is sent via hidden input.

                            submitButton.click(); // This submits the form
                            console.log('Form submission initiated with hidden club input.');

                            // Revert UI state *after* submission is triggered
                            roleSelectElement.disabled = true;
                            clubSelectElement.disabled = true;
                            this.textContent = 'Edit';
                            this.classList.remove('text-green-600', 'hover:text-green-900', 'dark:text-green-400', 'dark:hover:text-green-600');
                            this.classList.add('text-indigo-600', 'hover:text-indigo-900', 'dark:text-indigo-400', 'dark:hover:text-indigo-600');
                            row.classList.remove('editing-row');
                            console.log('Role and Club dropdowns disabled after submission.');
                        }
                    });
                });

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.preventDefault();
                        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                            this.closest('form').submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>