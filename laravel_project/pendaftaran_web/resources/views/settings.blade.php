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
                                value="{{ request('cari') }}" {{-- Keep the search term in the input --}}
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
                    <div class="overflow-x-auto"> {{-- Add this div for horizontal scrolling on small screens --}}
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
                                        Role
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $key + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->email }}
                                        </td>                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{-- Use a form for role changes --}}
                                            <form id="role-form-{{ $user->id }}"
                                                action="{{ route('settings.update-role', $user) }}" method="POST">
                                                @csrf
                                                @method('PATCH') {{-- Use PATCH for updates --}}
                                                <select name="role"
                                                    class="form-select role-select p-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    disabled>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                {{-- A hidden submit button to be triggered by JS --}}
                                                <button type="submit" class="hidden update-role-submit-btn"></button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{-- Edit Button --}}
                                            <button type="button"
                                                class="edit-button text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-md px-2 py-1">
                                                Edit
                                            </button>

                                            {{-- Delete Button --}}
                                            <form action="{{ route('settings.destroy-user', $user) }}" method="POST"
                                                class="inline-block delete-user-form ml-2">
                                                @csrf
                                                @method('DELETE') {{-- Use DELETE for deletion --}}
                                                <button type="submit"
                                                    class="delete-button text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-md px-2 py-1">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- End of overflow-x-auto div --}}

                    {{-- Pagination Links --}}
                    {{-- <div class="mt-4">
                        {{ $users->links() }}
                    </div>                     --}}

                    <p class="mt-4 text-gray-700 dark:text-gray-300">You can add forms, options, and other settings
                        here.</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const editButtons = document.querySelectorAll('.edit-button');
                const deleteButtons = document.querySelectorAll('.delete-button');

                editButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const row = this.closest('tr');
                        const selectElement = row.querySelector('.role-select');
                        const submitButton = row.querySelector('.update-role-submit-btn'); // Get hidden submit button

                        if (!selectElement) { // Safety check
                            console.error('Error: Could not find the role select element for this row.');
                            return;
                        }

                        if (selectElement.disabled) {
                            // Current state: "Edit" button, dropdown is disabled.
                            // Action: Enable dropdown, change button to "Save".
                            selectElement.disabled = false; // ENABLE THE SELECT
                            this.textContent = 'Save';
                            this.classList.remove('text-indigo-600', 'hover:text-indigo-900', 'dark:text-indigo-400', 'dark:hover:text-indigo-600');
                            this.classList.add('text-green-600', 'hover:text-green-900', 'dark:text-green-400', 'dark:hover:text-green-600');
                            row.classList.add('editing-row');
                            console.log('Role dropdown enabled for editing.');
                        } else {
                            // Current state: "Save" button, dropdown is enabled.
                            // Action: Submit form, then disable dropdown, change button to "Edit".

                            // >>>>>> THE CRUCIAL CHANGE: Submit the form *FIRST* <<<<<<
                            if (submitButton) {
                                submitButton.click(); // This submits the form while select is ENABLED
                                console.log('Form submission initiated while select is ENABLED.');
                            } else {
                                console.error('Error: Could not find the hidden submit button for submission.');
                            }

                            // Now, after submission is triggered, revert the UI state
                            selectElement.disabled = true; // DISABLE THE SELECT *AFTER* SUBMISSION
                            this.textContent = 'Edit';
                            this.classList.remove('text-green-600', 'hover:text-green-900', 'dark:text-green-400', 'dark:hover:text-green-600');
                            this.classList.add('text-indigo-600', 'hover:text-indigo-900', 'dark:text-indigo-400', 'dark:hover:text-indigo-600');
                            row.classList.remove('editing-row');
                            console.log('Role dropdown disabled after submission.');
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

    {{-- @push('scripts')
    <script>
        console.log('*** SCRIPT BLOCK STARTED ***'); // TOP-LEVEL CHECK 1

        document.addEventListener('DOMContentLoaded', function () {
            console.log('*** DOMContentLoaded fired! ***'); // TOP-LEVEL CHECK 2

            const editButtons = document.querySelectorAll('.edit-button');
            const deleteButtons = document.querySelectorAll('.delete-button');

            console.log('Number of edit buttons found:', editButtons.length); // DIAGNOSTIC: Should be > 0
            console.log('Number of delete buttons found:', deleteButtons.length); // Already confirmed, but keep for full picture

            editButtons.forEach(button => {
                console.log('Edit button found (inside forEach):', button); // DIAGNOSTIC: Should run for each edit button

                button.addEventListener('click', function () {
                    console.log('Edit button clicked!'); // DIAGNOSTIC: Is the click event firing?
                    const row = this.closest('tr');
                    console.log('Parent row found:', row); // DIAGNOSTIC: Is the row found?

                    const selectElement = row.querySelector('.role-select');
                    console.log('Role select element found:', selectElement); // DIAGNOSTIC: Is the select element found?

                    if (!selectElement) {
                        console.error('Error: Could not find the role select element for this row.');
                        return; // Stop execution if select element not found
                    }

                    // Toggle disabled state
                    selectElement.disabled = !selectElement.disabled;
                    console.log('Select element disabled state toggled to:', selectElement.disabled); // DIAGNOSTIC

                    // Optionally, change button text or appearance
                    if (!selectElement.disabled) {
                        this.textContent = 'Save'; // Change text to 'Save'
                        this.classList.remove('text-indigo-600', 'hover:text-indigo-900', 'dark:text-indigo-400', 'dark:hover:text-indigo-600');
                        this.classList.add('text-green-600', 'hover:text-green-900', 'dark:text-green-400', 'dark:hover:text-green-600');
                        row.classList.add('editing-row');
                        console.log('Edit button changed to Save state.');
                    } else {
                        this.textContent = 'Edit'; // Change text back to 'Edit'
                        this.classList.remove('text-green-600', 'hover:text-green-900', 'dark:text-green-400', 'dark:hover:text-green-600');
                        this.classList.add('text-indigo-600', 'hover:text-indigo-900', 'dark:text-indigo-400', 'dark:hover:text-indigo-600');
                        row.classList.remove('editing-row');
                        console.log('Edit button changed to Edit state (should trigger save).');

                        // If the dropdown was re-disabled (meaning 'Save' was clicked)
                        // Trigger the form submission for role update
                        const submitButton = row.querySelector('.update-role-submit-btn');
                        if (submitButton) {
                            submitButton.click();
                            console.log('Hidden submit button clicked.'); // DIAGNOSTIC
                        } else {
                            console.error('Error: Could not find the hidden submit button.');
                        }
                    }
                });
            });

            deleteButtons.forEach(button => {
                console.log('Delete button found (inside forEach):', button); // DIAGNOSTIC: Should run for each button
                button.addEventListener('click', function (event) {
                    console.log('Delete button clicked!');
                    event.preventDefault();

                    const formElement = this.closest('form');
                    console.log('Found form:', formElement);

                    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                        console.log('Confirmation received: YES');
                        if (formElement) {
                            formElement.submit();
                            console.log('Form submission initiated.');
                        } else {
                            console.error('Error: Could not find the parent form for deletion.');
                        }
                    } else {
                        console.log('Confirmation received: NO');
                    }
                });
            });

            console.log('*** SCRIPT BLOCK ENDED ***'); // TOP-LEVEL CHECK 3
        });
    </script>
    @endpush --}}
</x-app-layout>