<x-admin-layout>
    <table class="border-collapse border border-gray-200">
        <thead>
            <tr>
                <th class="border border-gray-500 p-2">Group</th>
                <th class="border border-gray-500 p-2">Name</th>
                <th class="border border-gray-00 p-2">Payload</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedSettings as $group => $groupSettings)
            @foreach($groupSettings as $setting)
            <tr>
                <td class="border border-gray-200 p-2">{{ $group }}</td>
                <td class="border border-gray-200 p-2">{{ $setting->name }}</td>
                <td class="border border-gray-200 p-2">{{ $setting->payload }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</x-admin-layout>