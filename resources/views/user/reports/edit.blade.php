<x-admin-layout>
    <div class="container mx-auto mt-8">
        <h2 class="text-3xl font-bold mb-4">Edit Report Visibility</h2>
        <form method="post" action="{{ route('user.report.update', $report->id) }}" class="max-w-md mx-auto bg-white p-8 rounded shadow-md">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Report Domain:</label>
                <p class="text-lg">{{ $report->domain }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Visibility:</label>
                <div>
                    <label class="inline-flex items-center">
                        <input type="radio" name="visibility" value="public" {{ $report->visibility === 'public' ? 'checked' : '' }}>
                        <span class="ml-2">Public</span>
                    </label>

                    <label class="inline-flex items-center ml-4">
                        <input type="radio" name="visibility" value="private" {{ $report->visibility === 'private' ? 'checked' : '' }}>
                        <span class="ml-2">Private</span>
                    </label>

                    <label class="inline-flex items-center ml-4">
                        <input type="radio" name="visibility" value="password" {{ $report->visibility === 'password' ? 'checked' : '' }}>
                        <span class="ml-2">Password</span>
                    </label>
                </div>
            </div>
            <div class="mb-4" id="passwordField" style="@if($report->visibility === 'password') @else display: none; @endif">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded shadow appearance-none" value="{{ $report->visibility === 'password' ? $report->password : '' }}" {{ $report->visibility !== 'password' ? 'disabled' : '' }}>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Show Password:</label>
                    <input type="checkbox" id="showPassword">
                </div>
            </div>


            <div class="flex space-x-4 mt-4">
                <button type="submit" class="w-1/2 bg-blue-500 text-white rounded px-4 py-2 focus:outline-none hover:bg-blue-600">Update Visibility</button>
                <a href="{{ route('user.reports') }}" class="w-1/2 bg-blue-500 text-white text-center rounded px-4 py-2 focus:outline-none hover:bg-blue-600">Back</a>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordField = document.getElementById('passwordField');
                const passwordInput = document.querySelector('input[name="password"]');
                const showPasswordCheckbox = document.getElementById('showPassword');
                const visibilityRadios = document.querySelectorAll('input[name="visibility"]');

                // Function to toggle password visibility
                function togglePasswordVisibility() {
                    const inputType = showPasswordCheckbox.checked ? 'text' : 'password';
                    passwordInput.type = inputType;
                }

                // Event listener for visibility radios
                visibilityRadios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        passwordField.style.display = (this.value === 'password' && this.checked) ? 'block' : 'none';
                        passwordInput.required = (this.value === 'password' && this.checked);
                        passwordInput.disabled = (this.value !== 'password');
                    });
                });

                // Event listener for show password checkbox
                showPasswordCheckbox.addEventListener('change', togglePasswordVisibility);
            });
        </script>

    </div>
</x-admin-layout>