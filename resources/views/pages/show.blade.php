<x-admin-layout>
    <div class="container mx-auto p-8">


        @foreach($pagesRecord as $page)
        <h1 class="text-3xl font-bold mb-4">{{$page->type}}</h1>
        <form method="post" action="{{ route('admin.page.update', ['id' => $page->id]) }}" class="max-w-md mx-auto">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                    Name: *
                </label>
                <input type="text" name="name" value="{{ $page->name }}" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
            </div>

            <div class="mb-4">
                <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">
                    Slug: *
                </label>
                <input type="text" name="slug" value="{{ $page->slug }}" class="w-full px-3 py-2 border rounded shadow appearance-none" @if($page->slug) readonly @endif
                required
                >
            </div>


            <div class="mb-4">
                <label for="data" class="block text-gray-700 text-sm font-bold mb-2">
                    Content: *
                </label>
                <textarea name="data" class="w-full px-3 py-2 border rounded shadow appearance-none" rows="9">{{ $page->data }}</textarea>
            </div>

            <div class="mb-4">
                <label for="visibility" class="block text-gray-700 text-sm font-bold mb-2">
                    Visibility: *
                </label>
                <select name="visibility" class="w-full px-3 py-2 border rounded shadow appearance-none" required>
                    <option value="unlisted" {{ $page->visibility == 'unlisted' ? 'selected' : '' }}>Unlisted</option>
                    <option value="footer" {{ $page->visibility == 'footer' ? 'selected' : '' }}>Footer</option>
                </select>
            </div>

            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Update</button>
            <a href=" {{route('admin.pages.index')}}">
                <button type="button" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Back</button>
            </a>

        </form>
        @endforeach
    </div>
</x-admin-layout>