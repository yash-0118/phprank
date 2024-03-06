<x-admin-layout>
    <div class="py-12 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between border-b border-gray-500">
                    <p class="text-2xl font-bold text-gray-900">Users</p>
                    <form action="#" method="get" class="flex items-center">
                        <input type="text" name="search" placeholder="Search" class="border rounded-md p-2" value="{{ request('search') }}">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-2">Search</button>
                        <button type="button" onclick="cancelSearch()" class="bg-gray-500 text-white px-4 py-2 rounded-md ml-2">Cancel</button>
                    </form>
                </div>
                <div class="p-6">
                    @if(count($usersWithRoles) > 0)
                    @foreach($usersWithRoles as $data)
                    @php
                    $user = $data['user'];
                    $role = $data['role'];
                    @endphp
                    <div class="flex items-center justify-between mb-4 border-b border-gray-500">
                        <div class="flex space-x-4">
                            <p class="text-xl font-bold">{{ $user->name }}</p>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                        <div>
                            @if($role==1)
                            <p class="text-blue-600  bg-blue-200 rounded-md p-1">Admin</p>
                            @else
                            <p class="text-blue-600  bg-blue-200 rounded-md p-1">User</p>
                            @endif
                        </div>
                        <div>
                            @if($user->is_active)
                            <p class="text-green-500 bg-green-200 rounded-md p-1">Active</p>
                            @else
                            <p class="text-red-500 bg-red-200 rounded-md p-1">Inactive</p>
                            @endif
                        </div>
                        <div class="flex space-x-4">
                            @if($user->is_active && $role != 1)
                            <form method="POST" action="{{ route('impersonate.user', $user) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Login</button>
                            </form>
                            @else
                            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md cursor-not-allowed opacity-50" disabled>Login</button>
                            @endif

                            <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}"><button class="bg-blue-500 text-white px-4 py-2 rounded-md">Edit</button></a>
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
    <script>
        function cancelSearch() {
            document.querySelector('input[name="search"]').value = '';
            document.querySelector('form[action="#"]').submit();
        }
    </script>
</x-admin-layout>