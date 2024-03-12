<x-admin-layout>
    <h1 class="text-2xl font-bold mb-4">User : {{$user->name}}</h1>
    <h1 class="text-2xl font-bold mb-4">Email : {{$user->email}}</h1>
    <a href="/reports">
        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Report</button>
    </a>
    @foreach($categoryPercentages as $category => $percentage)
    <p>{{ ucfirst($category) }}: {{ $percentage }}%</p>
    @endforeach
    <h1 class="text-2xl font-bold mb-4">Latest Reports</h1>
    <div>
        @if(count($reports) > 0)
        @foreach($reports as $report)
        <div class="mb-4">
            <a href="/report/{{$report->id}}" class="text-blue-500 no-underline hover:underline">
                {{$report->domain}}
            </a>
            <span class="text-black-600">{{$report->category}}</span>
            <span class="text-gray-600">{{$report->created_at->diffForHumans()}}</span>
        </div>
        @endforeach
        <a href="/reports">
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View all</button>
        </a>
        @else
        <p class="text-gray-500">No Data Available</p>
        @endif
    </div>
    <div class="mt-8">
        <h1 class="text-2xl font-bold mb-4">Latest Projects</h1>
        @if(count($projects) > 0)
        @foreach($projects as $project)
        <div class="mb-4">
            <a href="/project/{{$project->domain}}" class="text-blue-500 no-underline hover:underline">
                {{$project->domain}}
            </a>
            <span class="text-gray-600">{{$project->created_at->diffForHumans()}}</span>
        </div>
        @endforeach
        <a href="/projects">
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View all</button>
        </a>
        @else
        <p class="text-gray-500">No Data Available</p>
        @endif
    </div>
</x-admin-layout>