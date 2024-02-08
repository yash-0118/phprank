<x-admin-layout>
    <div class="py-12 w-full">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-3xl font-bold mb-4">New Page</h1>

                    <form method="post" action="{{ route('admin.pages.store') }}" class="max-w-md mx-auto">
                        @csrf

                        <div class="mb-4">
                            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">
                                Type: *
                            </label>
                            <input type="text" name="type" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                        </div>

                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                                Name: *
                            </label>
                            <input type="text" name="name" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                        </div>

                        <div class="mb-4">
                            <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">
                                Slug: *
                            </label>
                            <input type="text" name="slug" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                        </div>
                        <div class="mb-4">
                            <label for="visibility" class="block text-gray-700 text-sm font-bold mb-2">
                                Visibility: *
                            </label>
                            <select name="visibility" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                                <option value="unlisted">Unlisted</option>
                                <option value="footer">Footer</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="data" class="block text-gray-700 text-sm font-bold mb-2">
                                Content: *
                            </label>
                            <textarea name="data" class="w-full px-3 py-2 border rounded shadow appearance-none" required></textarea>
                        </div>


                        <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Create Page</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
