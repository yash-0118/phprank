<x-admin-layout>
    <div class="container mx-auto p-8">


        <h1 class="text-3xl font-bold mb-4">Appearance Settings</h1>
        @if(session('error'))
        <span>{{ session('error') }}</span>
        @endif
        @if(count($settingsRecords) > 0)
        <form method="post" action="{{ route('save-settings', ['group' => 'appearance']) }}" class="max-w-md mx-auto" enctype="multipart/form-data">
            @csrf

            @foreach($settingsRecords as $settingsRecord)
           
            <!-- @if($settingsRecord->name == 'logo')
            @php
            $name = $settingsRecord->payload;
            $filePath = asset('storage/' . $name);
            @endphp
            <img src="{{ $filePath }}" alt="Image">
            @endif -->
            <div class="mb-4">
                <label for="{{ $settingsRecord->name }}" class="block text-gray-700 text-sm font-bold mb-2">
                    {{ ucfirst($settingsRecord->name) }}:
                </label>
                @if($settingsRecord->name == 'logo' || $settingsRecord->name == 'favicon')
                <input type="file" name="{{ $settingsRecord->name }}" class="w-full px-3 py-2 border rounded shadow appearance-none" accept="image/*">
                @elseif($settingsRecord->name == 'theme')
                <select name="{{ $settingsRecord->name }}" class="w-full px-3 py-2 border rounded shadow appearance-none">
                    <option value="light" {{ $settingsRecord->payload == 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark" {{ $settingsRecord->payload == 'dark' ? 'selected' : '' }}>Dark</option>

                </select>
                @elseif($settingsRecord->name == 'custom_css')
                <textarea name="{{ $settingsRecord->name }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>{{ $settingsRecord->payload }} </textarea>
                @else
                <input type="text" name="{{ $settingsRecord->name }}" value="{{ $settingsRecord->payload }}" class="w-full px-3 py-2 border rounded shadow appearance-none">
                @endif
            </div>
            @endforeach

            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Save</button>
        </form>
        @else
        <p class="mt-4">No settings found for Appearance group.</p>
        @endif
    </div>
</x-admin-layout>