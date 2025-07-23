<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Search Thesis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('user.thesis.index') }}" class="mb-6 flex flex-wrap gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student or title"
                        class="border-gray-300 rounded px-3 py-2 shadow-sm">

                    <select name="year" class="border-gray-300 rounded px-3 py-2 shadow-sm">
                        <option value="">All Years</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>

                    {{-- <select name="type" class="border-gray-300 rounded px-3 py-2 shadow-sm">
                        <option value="">All Types</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select> --}}

                    <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded">Filter</button>
                    <a href="{{ route('user.thesis.index') }}" class="bg-gray-300 text-black px-4 py-2 rounded">Reset</a>
                </form>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Degree Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">University</th> --}}
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Document</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($theses as $thesis)
                        <tr>
                            <td class="px-6 py-4">{{ $thesis->name }}</td>
                            <td class="px-6 py-4">{{ $thesis->year }}</td>
                            <td class="px-6 py-4">{{ $thesis->title }}</td>
                            {{-- <td class="px-6 py-4 capitalize">{{ $thesis->type }}</td>
                            <td class="px-6 py-4">{{ $thesis->university }}</td> --}}
                            <td class="px-6 py-4">
                                @if ($thesis->document_path)
                                    <a href="{{ Storage::url($thesis->document_path) }}" target="_blank" class="text-blue-600 underline">View</a>
                                @else
                                    <span class="text-gray-400">No document</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">No thesis found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $theses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
