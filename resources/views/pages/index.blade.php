<x-admin-layout>
    <div class="py-12 w-full">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('admin.pages.create') }}"><button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">New Page</button></a>
                    <h1 class="text-3xl font-bold mb-4">Pages</h1>

                    <ul class=" pl-4">
                        @foreach($types as $type)
                        <li class="flex items-center">
                            <a href="{{ route('admin.pages.edit', ['id' => $type->id]) }}" class="text-blue-500 hover:underline text-xl  flex-grow">
                                {{ $type->type }}
                            </a>
                            <form method="post" action="{{ route('admin.pages.destroy', ['id' => $type->id]) }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="mt-2 px-3 py-1 bg-red-400 text-white rounded">Delete</button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
