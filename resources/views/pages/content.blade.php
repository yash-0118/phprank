<x-admin-layout>
    @foreach($pages as $page)
    <div class="bg-white w-full p-6 mb-6 rounded-md shadow-md">
        <h1 class="text-2xl font-bold mb-2">{{$page->name}}</h1>
        <p class="text-gray-600">Updated at: {{$page->updated_at}}</p>
        <div class="mt-4 text-gray-700">
            {!! $page->data !!}
        </div>
    </div>
    @endforeach
</x-admin-layout>