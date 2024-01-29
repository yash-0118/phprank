<x-admin-layout>
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-4">Social Settings</h1>

        @if(count($settingsRecords) > 0)
        <form method="post" action="{{ route('save-settings', ['group' => 'social']) }}" class="max-w-md mx-auto">
            @csrf

            @foreach($settingsRecords as $settingsRecord)
            <div class="mb-4">
                <label for="{{ $settingsRecord->name }}" class="block text-gray-700 text-sm font-bold mb-2">
                    {{ ucfirst($settingsRecord->name) }}: *
                </label>
                <input type="text" name="{{ $settingsRecord->name }}" value="{{ $settingsRecord->payload }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
            </div>
            @endforeach

            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Save</button>
        </form>
        @else
        <p class="mt-4">No settings found for Social group.</p>
        @endif
    </div>
</x-admin-layout>