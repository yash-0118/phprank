<x-admin-layout>
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-4">{{ ucfirst($group) }} Settings</h1>

        @if(count($settingsRecords) > 0)
        <form method="post" action="{{ route('save-settings', ['group' => $group]) }}">
            @csrf
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Name</th>
                        <th class="py-2 px-4 border-b">Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($settingsRecords as $settingsRecord)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $settingsRecord->name }}</td>
                        <td class="py-2 px-4 border-b">
                            <input type="text" name="{{ $settingsRecord->name }}" value="{{ $settingsRecord->payload }}" class="w-full">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Save</button>
        </form>
        @else
        <p class="mt-4">No settings found for {{ ucfirst($group) }}.</p>
        @endif
    </div>
</x-admin-layout>