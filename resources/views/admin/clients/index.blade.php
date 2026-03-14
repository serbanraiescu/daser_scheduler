<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Clients') }}
            </h2>
            <div class="flex space-x-2">
                <form action="{{ route('admin.clients.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                    @csrf
                    <input type="file" name="csv_file" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                        Import CSV
                    </button>
                </form>
                <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150">
                    Add Client
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <form action="{{ route('admin.clients.index') }}" method="GET" class="flex">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, phone, or email..." class="w-full rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-r-md hover:bg-indigo-700 transition">Search</button>
                </form>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tags</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($clients as $client)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $client->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">{{ $client->phone }}</div>
                                    <div class="text-xs text-gray-500">{{ $client->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php $tags = is_array($client->tags) ? $client->tags : []; @endphp
                                    @foreach($tags as $tag)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mr-1">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.clients.show', $client) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                    <a href="{{ route('admin.clients.edit', $client) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $clients->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
