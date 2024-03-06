<x-admin-layout>
    @if(session('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" class="fixed bg-red-500 text-white py-2 px-4 rounded-xl bottom-3 right-3 text-sm">
        <p>{{ session('error') }}</p>
    </div>
    @endif
    <div class="py-12 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between border-b border-gray-500">
                    <form method="post" action="{{ route('user.report.save') }}" class="flex items-center w-full">
                        @csrf
                        <input type="text" name="search" required placeholder="https://www.example.com" class="border rounded-md p-2 flex-grow">

                        <input type="radio" id="report" name="search_option" value="report" checked class="ml-2">
                        <label for="report" class="ml-2 cursor-pointer">Report</label>

                        <input type="radio" id="sitemap" name="search_option" value="sitemap" class="ml-2">
                        <label for="sitemap" class="ml-2 cursor-pointer">Sitemap</label>
                        <div class="border-r mx-4 h-8"></div>
                        <div>
                            <input type="radio" id="public" name="visibility" value="public" class="ml-2">
                            <label for="public" class="ml-2 cursor-pointer">Public</label>

                            <input type="radio" id="private" name="visibility" value="private" checked class="ml-2">
                            <label for="private" class="ml-2 cursor-pointer">Private</label>

                            <input type="radio" id="password" name="visibility" value="password" class="ml-2">
                            <label for="password" class="ml-2 cursor-pointer">Password</label>

                            <input type="password" id="passwordInput" name="password" placeholder="Enter password" class="border rounded-md p-2 flex-grow ml-2" style="display: none;">
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-2">Analyze</button>
                    </form>

                </div>
                <br>
                @php
                $previousSearch = request('search') ?? ''; // Get the previous search string
                @endphp

                <form action="#" method="get" class="flex-right mb-4">
                    <input type="text" name="search" id="searchInput" placeholder="Search" class="border rounded-md p-2" value="{{ $previousSearch }}">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-2">Search</button>
                    <button type="button" onclick="cancelSearch()" class="bg-gray-500 text-white px-4 py-2 rounded-md ml-2">Cancel</button>
                </form>

                <div class="p-3 table-container">
                    @if(count($sites) > 0)
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xl">
                                <th>Domain</th>
                                <th>Score</th>
                                <th>Category</th>
                                <th>Created</th>
                                <th>operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sites as $site)
                            <tr class="text-lg">
                                <td><a href="/report/{{$site->id}}" class="text-blue-500 no-underline">{{$site->domain}}</a></td>
                                <td>{{$site->score}}</td>
                                <td>{{$site->category}}</td>
                                <td class="text-sm text-gray-500">{{$site->created_at}}</td>
                                <td>
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button @click="open = !open" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                            Actions
                                        </button>

                                        <div x-show="open" @click.away="open = false" id="actionsMenu" class="absolute z-50 mt-2 bg-white border border-gray-300 rounded-md shadow-lg">
                                            <ul>
                                                <li><a href="/report/{{$site->id}}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">View</a></li>
                                                <li><a href="{{$site->url}}" target="_blank" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Open</a></li>
                                                <li><a href="{{route('user.report.edit', ['id' => $site->id])}}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Edit</a></li>
                                            </ul>
                                            <form method="post" action="{{ route('user.report.delete', ['id' => $site->id]) }}" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="block w-full px-4 py-2 text-white bg-red-500 rounded-md">Delete</button>
                                            </form>
                                        </div>
                                    </div>


                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>No data</p>
                    @endif
                </div>
            </div>
        </div>
        <br>
        {{ $sites->links() }}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.querySelector('input[name="password"]');
            const visibilityRadios = document.querySelectorAll('input[name="visibility"]');

            visibilityRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    passwordInput.style.display = (this.value === 'password' && this.checked) ? 'block' : 'none';
                    passwordInput.required = (this.value === 'password' && this.checked);
                });
            });
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const isFirstCharacterAlphanumeric = /^[^\s]/.test(password);

                if (!isFirstCharacterAlphanumeric) {
                    this.setCustomValidity('There must not be whitespace at the begining of the password');
                } else {
                    this.setCustomValidity('');
                }
            });
        });

        function performAction(select) {
            const selectedOption = select.options[select.selectedIndex];
            const target = selectedOption.getAttribute('data-target');

            if (target) {
                window.open(selectedOption.value, '_blank');
            } else {
                window.location.href = selectedOption.value;
            }
        }

        function cancelSearch() {
            document.getElementById('searchInput').value = '';
            document.querySelector('form[action="#"]').submit();
        }
    </script>


</x-admin-layout>