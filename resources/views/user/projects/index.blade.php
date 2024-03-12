<x-admin-layout>
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Reports</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
           
            <tr>
                <td class="py-2 px-4 border-b">
                    <a href="/project/{{$project->domain}}" class="text-blue-500 hover:underline">
                        {{$project->domain}}
                    </a>
                </td>
                <td class="py-2 px-4 border-b">{{$result[$project->domain]}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $projects->links() }}
    </div>
</x-admin-layout>