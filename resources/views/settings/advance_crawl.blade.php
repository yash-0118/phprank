<x-admin-layout>
    <x-advance-layout>
        <div class="container mx-auto p-8">
            <h1 class="text-3xl font-bold mb-4">Advance->crawl Settings</h1>

            @if(count($settingsRecords) > 0)
            <form method="post" action="{{ route('save-settings', ['group' => 'advance_crawl']) }}" class="max-w-md mx-auto">
                @csrf

                @foreach($settingsRecords as $settingsRecord)
                <div class="mb-4">
                    <label for="{{ $settingsRecord->name }}" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ ucfirst($settingsRecord->name) }}: *
                    </label>
                    @if($settingsRecord->name == 'links_per_sitemaps')
                    <input type="number" name="{{ $settingsRecord->name }}" value="{{ $settingsRecord->payload }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                    @else
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