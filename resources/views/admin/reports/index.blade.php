<x-admin-layout>
    <p>
        @if (session()->has('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" class="fixed bg-green-500 text-white py-2 px-4 rounded-xl bottom-3 right-3 text-sm">
        <p>{{ session('success') }}</p>
    </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Report</th>
                                <th class="px-4 py-2">User</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                            <tr>

                                <td class="border px-4 py-2"> <a href="{{ route('admin.reports.edit', ['id' => $report->id]) }}" class="text-blue-500">{{ $report->domain }}</a></td>


                                <td class="border px-4 py-2 text-blue-500"> <a href="{{route('admin.user.edit', ['id' => $report->user_id])}}">{{ $report->user->name }} </a></td>
                                <td class="border px-4 py-2 text-blue-500">
                                    <form method="post" action="{{ route('admin.report.delete', ['id' => $report->id]) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            {{ $reports->links() }}
        </div>
    </div>
</x-admin-layout>