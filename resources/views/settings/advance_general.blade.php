<x-admin-layout>
    <x-advance-layout>
        <div class="container mx-auto p-8">
            <h1 class="text-3xl font-bold mb-4">Advance->general Settings</h1>

            @if(count($settingsRecords) > 0)
            <form method="post" action="{{ route('save-settings', ['group' => 'advance_general']) }}" class="max-w-md mx-auto">
                @csrf

                @foreach($settingsRecords as $settingsRecord)
                <div class="mb-4">

                    @if($settingsRecord->name == 'bad_words')
                    <label for="{{ $settingsRecord->name }}" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ ucfirst($settingsRecord->name) }}:
                    </label>
                    <input type="text" name="{{ $settingsRecord->name }}" value="{{ $settingsRecord->payload }}" class="w-full px-3 py-2 border rounded shadow appearance-none">

                    @else
                    <label for="{{ $settingsRecord->name }}" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ ucfirst($settingsRecord->name) }}: *
                    </label>
                    <input type="text" name="{{ $settingsRecord->name }}" value="{{ $settingsRecord->payload }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                    @endif
                </div>
                @endforeach

                <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </form>
            @else
            <p class="mt-4">No settings found for general group.</p>
            @endif
        </div>
    </x-advance-layout>
</x-admin-layout>