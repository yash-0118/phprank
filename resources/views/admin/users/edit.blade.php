<x-admin-layout>
    <div class="container mx-auto mt-8">
        <h2 class="text-3xl font-bold mb-4">Edit User</h2>
        <form method="post" action="{{ route('admin.user.update', $user->id) }}" class="max-w-md mx-auto bg-white p-8 rounded shadow-md">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border rounded shadow appearance-none" value="{{ $user->name }}" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded shadow appearance-none bg-gray-100" value="{{ $user->email }}" readonly>
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
                <select name="role" id="role" class="w-full px-3 py-2 border rounded shadow appearance-none">
                    <option value="user" {{ $user->hasRole('user') ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="w-full bg-blue-500 text-white rounded px-4 py-2">Save</button>
                <a href="{{ route('admin.users.index') }}" class="w-full bg-blue-500 text-white text-center rounded px-4 py-2">Back</a>
            </div>
        </form>
    </div>
</x-admin-layout>