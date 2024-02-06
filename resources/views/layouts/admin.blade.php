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
    <title>{{ $settings['title'] ?? '1'}}</title>
    @if(isset($settings['favicon']) && file_exists(public_path('storage/' . $settings['favicon'])))
    <link rel="icon" type="image/svg" href="{{ asset('storage/' . $settings['favicon'] )}}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('storage/' . 'default/sk33lBTdJBSH24lCddJtEVQGGBqD7Kli180AXLQI.svg') }}">
    @endif
</head>

<body class="antialiased bg-gray-{{$settings['theme']=='dark'?'500':'200'}}">


    <div class="flex-col flex w-full md:flex md:flex-row md:min-h-screen">
        <div @click.away="open = false" class="flex flex-col flex-shrink-0 w-full  bg-white md:w-64  bg-gray-{{$settings['theme']=='dark'?'700':'400'}}" x-data="{ open: false }">
            <div class="flex flex-row items-center justify-between flex-shrink-0 px-8 py-4">
                <a href="{{ route('admin.dashboard.index') }}" class="text-lg font-semibold tracking-widest text-gray-900 uppercase rounded-lg focus:outline-none focus:shadow-outline">
                    @if(isset($settings['logo']) && file_exists(public_path('storage/' . $settings['logo'])))
                    <img src="{{ asset('storage/' . $settings['logo'] )}}" class="rounded-xl" style="width: 100px; height:100px" alt="Logo">
                    @else
                    <img src="{{ asset('storage/' . 'default/sk33lBTdJBSH24lCddJtEVQGGBqD7Kli180AXLQI.svg') }}" class="rounded-xl" style="width: 100px; height:100px" alt="Default Logo"></a>
                @endif
                <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                        <path x-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        <path x-show="open" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            @role('admin')
            <nav :class="{'block': open, 'hidden': !open}" class="flex-grow px-4 pb-4 md:block md:pb-0 bg-gray-{{$settings['theme']=='dark'?'700':'400'}} md:overflow-y-auto">
                <a class="block px-4 py-2 mt-2 text-sm font-semibold {{ request()->routeIs('admin.dashboard.index') ? 'bg-gray-200 text-gray-900 shadow-md' : 'text-gray-900' }} rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                <div @click.away="open = false" class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex flex-row items-center w-full px-4 py-2 mt-2 text-sm font-semibold text-left bg-transparent rounded-lg md:block hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                        <span>Settings</span>
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-full mt-2 origin-top-right rounded-md shadow-lg">
                        <div class="px-2 py-2 bg-white rounded-md shadow">
                            <a class="block px-4 py-2 mt-2 text-sm font-semibold rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{url('admin/setting/general')}}">General</a>
                            <a class="block px-4 py-2 mt-2 text-sm font-semibold rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{url('admin/setting/advance-general')}}">Advanced</a>
                            <a class="block px-4 py-2 mt-2 text-sm font-semibold  rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{url('admin/setting/appearance')}}">Apperence</a>
                            <a class="block px-4 py-2 mt-2 text-sm font-semibold  rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{url('admin/setting/social')}}">Social</a>
                            <a class="block px-4 py-2 mt-2 text-sm font-semibold  rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{url('admin/setting/announcement-guest')}}">Announcements</a>
                        </div>
                    </div>
                </div>
                <a class="block px-4 py-2 mt-2 text-sm font-semibold {{ request()->routeIs('admin.users.index') ? 'bg-gray-200 text-gray-900 shadow-md' : 'text-gray-900' }} rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{route('admin.users.index')}}">Users</a>
                <a class="block px-4 py-2 mt-2 text-sm font-semibold {{ request()->routeIs('admin.pages.index') ? 'bg-gray-200 text-gray-900 shadow-md' : 'text-gray-900' }} rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{route('admin.pages.index')}}">Pages</a>
                <a class="block px-4 py-2 mt-2 text-sm font-semibold {{ request()->routeIs('admin.reports.index') ? 'bg-gray-200 text-gray-900 shadow-md' : 'text-gray-900' }} rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{ route('admin.reports.index') }}">
                    Reports
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="block px-4 py-2 mt-2 text-sm font-semibold {{ request()->routeIs('logout') ? 'bg-gray-200 text-gray-900 shadow-md' : 'text-gray-900' }} rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                        Log Out
                    </button>
                </form>



            </nav>
        </div>
        <div class="flex flex-col w-full bg-slate-50">

            <div class="flex w-full bg-slate-50">

                {{$slot}}
                <p>
                    @if(session('success'))
                    {{ session('success') }}
                    @endif
                </p>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>


            </div>
            <div class="fixed bottom-0 right-0 p-4 bg-gray-500">
                <div class="flex">
                    <a href="{{ url('admin/pages/terms') }}" class="mr-4 text-white">Terms</a>
                    <a href="{{ url('admin/pages/privacy') }}" class="mr-4 text-white">Privacy</a>
                </div>
            </div>

        </div>

        @else
        <nav :class="{'block': open, 'hidden': !open}" class="flex-grow px-4 pb-4 md:block md:pb-0 bg-gray-{{$settings['theme']=='dark'?'700':'400'}} md:overflow-y-auto">
            <a class="block px-4 py-2 mt-2 text-sm font-semibold {{ request()->routeIs('user.index') ? 'bg-gray-200 text-gray-900 shadow-md' : 'text-gray-900' }} rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{ route('user.index') }}">Dashboard</a>

            <a class="block px-4 py-2 mt-2 text-sm font-semibold {{ request()->routeIs('user.reports') ? 'bg-gray-200 text-gray-900 shadow-md' : 'text-gray-900' }} rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{route('user.reports')}}">Reports</a>
            <a class="block px-4 py-2 mt-2 text-sm font-semibold {{ request()->routeIs('user.projects') ? 'bg-gray-200 text-gray-900 shadow-md' : 'text-gray-900' }} rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="{{route('user.projects')}}">Projects</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="block px-4 py-2 mt-2 text-sm font-semibold {{ request()->routeIs('logout') ? 'bg-gray-200 text-gray-900 shadow-md' : 'text-gray-900' }} rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    Log Out
                </button>
            </form>



        </nav>
    </div>
    <div class="flex flex-col w-full bg-slate-50">
        @if($announcemet_user['content']=="null")
        @else
        <h1 class="text-2xl"> <span class="text-blue-500"> Announcements :</span> {{$announcemet_user['content']}}</h1>
        @endif
        <div class="flex w-full bg-slate-50">


            {{$slot}}
            <p>
                @if(session('success'))
                {{ session('success') }}
                @endif
            </p>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>


        </div>
        <div class="fixed bottom-0 right-0 p-4 bg-gray-500">
            <div class="flex">
                <a href="{{ url('pages/terms') }}" class="mr-4 text-white">Terms</a>
                <a href="{{ url('pages/privacy') }}" class="mr-4 text-white">Privacy</a>
            </div>
        </div>
    </div>
    @endrole


</body>

</html>