<x-admin-layout>
    <x-announcement-layout>
        <div class="container mx-auto p-8">
            <h1 class="text-3xl font-bold mb-4">Guests</h1>

            @if(count($settingsRecords) > 0)
            <form method="post" action="{{ route('save-settings', ['group' => 'announcementGuest']) }}" class="max-w-md mx-auto">
                @csrf

                @foreach($settingsRecords as $settingsRecord)
                <div class="mb-4">

                    <label for="{{ $settingsRecord->name }}" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ ucfirst($settingsRecord->name) }}: *
                    </label>
                    @if($settingsRecord->name == 'type')
                    <select name="{{ $settingsRecord->name }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                        <option value="primary" {{ $settingsRecord->payload == 'primary' ? 'selected' : '' }}>Primary</option>
                        <option value="danger" {{ $settingsRecord->payload == 'danger' ? 'selected' : '' }}>Danger</option>
                        <option value="success" {{ $settingsRecord->payload == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="warning" {{ $settingsRecord->payload == 'warning' ? 'selected' : '' }}>Warning</option>
                    </select>
                    @else
                    <input type="text" name="{{ $settingsRecord->name }}" value="{{ $settingsRecord->payload }}" class="w-full px-3 py-2 border rounded shadow appearance-none">
                    @endif
                    @endforeach

                    <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </form>
            @else
            <p class="mt-4">No settings found for Guest</p>
            @endif
        </div>
    </x-announcement-layout>
</x-admin-layout>