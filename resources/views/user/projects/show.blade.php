<x-admin-layout>
    @if(session('error'))
    <div class="text-red-500">
        {{ session('error') }}
    </div>
    @endif
    <div class="py-12 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between border-b border-gray-500">
                    <form action="{{ route('user.report.save') }}" method="post" class="flex items-center w-full">
                        @csrf
                        <input type="text" name="search" placeholder="https://www.example.com" class="border rounded-md p-2 flex-grow">
                        <input type="radio" name="search_option" value="report" checked class="ml-2"> report
                        <input type="radio" name="search_option" value="sitemap" class="ml-2"> Sitemap
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-2">Analyze</button>
                    </form>
                </div>
                <br>
                <div class="p-3 table-container">
                    @if(count($sites) > 0)
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xl">
                                <th>Domain</th>
                                <th>Score</th>
                                <th>Category</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sites as $site)
                            <tr class="text-lg">
                                <td><a href="/report/{{$site->id}}" class="text-blue-500 no-underline">{{$site->domain}}</a></td>
                                <td>{{$site->score}}</td>
                                <td>{{$site->category}}</td>
                                <td class="text-sm text-gray-500">{{$site->created_at->diffForHumans()}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>No data</p>
                    @endif
                </div>
            </div>
        </div>
        <br>
        {{ $sites->links() }}
    </div>
</x-admin-layout>
