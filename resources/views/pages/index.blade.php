<x-admin-layout>
    <div class="py-12 w-full">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-3xl font-bold mb-4">Pages</h1>

                    <ul class=" pl-4">
                        @foreach($types as $type)
                        <li>
                            <a href="{{ route('admin.pages.edit', ['id' => $type->id]) }}" class="text-blue-500 hover:underline text-xl">
                                {{ $type->type }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>