<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <title>phprank</title>
</head>

<body>
    <nav class="p-4">
        <div class="mx-auto flex justify-between items-center">
            <ul class="flex space-x-4">
                <li><a href="{{ url('admin/setting/advance_general') }}">General Settings</a></li>
                <li><a href="{{ url('admin/setting/advance_crawl') }}" >Crawler Settings</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mx-auto p-8">
        {{$slot}}
    </div>


</body>

</html>