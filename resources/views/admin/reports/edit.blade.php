<x-admin-layout>
    <div class="container mx-auto mt-8">
        <h2 class="text-3xl font-bold mb-4">Edit Report Visibility</h2>
        <form method="post" action="{{ route('admin.reports.update', $report->id) }}" class="max-w-md mx-auto bg-white p-8 rounded shadow-md">
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
                <input type="password" name="password" class="w-full px-3 py-2 border rounded shadow appearance-none" value="{{ $report->visibility === 'password' ? $report->password : '' }}" @if($report->visibility !== 'password') readonly @endif>
            </div>

            <div class="flex space-x-4 mt-4">
                <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2">Update Visibility</button>
                <a href="{{ route('admin.reports.index') }}" class="bg-gray-500 text-white rounded px-4 py-2">Back</a>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordField = document.getElementById('passwordField');
                const passwordInput = document.querySelector('input[name="password"]');
                const visibilityRadios = document.querySelectorAll('input[name="visibility"]');

                visibilityRadios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        passwordField.style.display = (this.value === 'password' && this.checked) ? 'block' : 'none';
                        passwordInput.readOnly = (this.value !== 'password');
                        passwordInput.required = (this.value === 'password' && this.checked);
                    });
                });
            });
        </script>
    </div>
</x-admin-layout>