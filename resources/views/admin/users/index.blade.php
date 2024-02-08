<x-admin-layout>
    <div class="py-12 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between border-b border-gray-500">
                    <p class="text-2xl font-bold text-gray-900">Users</p>
                    <a href="{{route('admin.users.index')}}"><button class="bg-blue-500 text-white px-4 py-2 rounded-md">All</button></a>
                    <form action="#" method="get" class="flex items-center">
                        <input type="text" name="search" placeholder="Search" class="border rounded-md p-2">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-2">Search</button>
                    </form>
                </div>
                <div class="p-6">
                    @if(count($users)>0)
                    @foreach($users as $user)
                    <div class="flex items-center justify-between mb-4 border-b border-gray-500">
                        <div class="flex space-x-4">
                            <p class="text-xl font-bold">{{ $user->name }}</p>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{route('admin.user.edit', ['id' => $user->id])}}"><button class="bg-blue-500 text-white px-4 py-2 rounded-md">Edit</button></a>
                            <form method="post" action="{{ route('admin.user.destroy', ['id' => $user->id]) }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <h1>No Users Available</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>