<x-admin-layout>
    <h1 class="text-2xl font-bold mb-2">Users</h1>
    @if(count($users)>0)
    @foreach($users as $user)
    <div class="flex">
        <p class="text-xl font-bold">{{ $user->name }}</p>
        <a href="{{route('admin.user.edit', ['id' => $user->id])}}">
            <p class="text-gray-600 text-lg pl-3">{{ $user->email }}</p>
        </a>


    </div>

    @endforeach
    <a href="/admin/users">
        <button class="bg-blue-500 mt-3 text-white p-1 rounded-md">View all</button>
    </a>
    @else
    <p>No User Available</p>
    @endif

</x-admin-layout>
