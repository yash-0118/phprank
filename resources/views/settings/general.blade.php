<x-admin-layout>
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-4">General Settings</h1>

        @if(count($settingsRecords) > 0)
        <form method="post" action="{{ route('save-settings', ['group' => 'general']) }}" class="max-w-md mx-auto">
            @csrf
            @foreach($settingsRecords as $settingsRecord)
            <div class="mb-4">
                <label for="{{ $settingsRecord->name }}" class="block text-gray-700 text-sm font-bold mb-2">
                    {{ ucfirst($settingsRecord->name) }}: *
                </label>
                @if($settingsRecord->name == 'result_per_page')
                <select name="{{ $settingsRecord->name }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                    <option value="10" {{ $settingsRecord->payload == '10' ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $settingsRecord->payload == '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $settingsRecord->payload == '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $settingsRecord->payload == '100' ? 'selected' : '' }}>100</option>
                </select>
                @elseif($settingsRecord->name == 'language')
                <select name="{{ $settingsRecord->name }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                    <option value="english" {{ $settingsRecord->payload == 'english' ? 'selected' : '' }}>English</option>
                    <option value="hindi" {{ $settingsRecord->payload == 'hindi' ? 'selected' : '' }}>Hindi</option>
                </select>
                @elseif($settingsRecord->name == 'timezone')
                <select name="{{ $settingsRecord->name }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                    <option value="utc" {{ $settingsRecord->payload == 'utc' ? 'selected' : '' }}>UTC</option>
                    <option value="gmt" {{ $settingsRecord->payload == 'gmt' ? 'selected' : '' }}>GMT</option>
                </select>
                @elseif($settingsRecord->name == 'custom_js')
                <textarea name="{{ $settingsRecord->name }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>{{ $settingsRecord->payload }}</textarea>
                @elseif($settingsRecord->name == 'custom_index')
                 <input type="number" name="{{ $settingsRecord->name }}" value="{{ $settingsRecord->payload }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                @else
                <input type="text" name="{{ $settingsRecord->name }}" value="{{ $settingsRecord->payload }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                @endif
            </div>
            @endforeach

            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Save</button>
        </form>
        @else
        <p class="mt-4">No settings found for General group.</p>
        @endif
    </div>
</x-admin-layout>