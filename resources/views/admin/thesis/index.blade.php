<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Manage Thesis') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mx-6 mt-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Thesis List</h3>
                <a href="{{ route('admin.thesis.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mb-4 inline-block">
                    + Add Thesis
                </a>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Student Name</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Year</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Title</th>
                                {{-- <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Degree Type</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">University</th> --}}
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Document</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($theses as $thesis)
                            <tr>
                                <td class="px-6 py-4">{{ $thesis->name }}</td>
                                <td class="px-6 py-4">{{ $thesis->year }}</td>
                                <td class="px-6 py-4 break-words">{{ $thesis->title }}</td>
                                {{-- <td class="px-6 py-4 capitalize">{{ $thesis->type }}</td>
                                <td class="px-6 py-4">{{ $thesis->university }}</td> --}}
                                <td class="px-6 py-4">
                                    @if ($thesis->document_path)
                                        <a href="{{ Storage::url($thesis->document_path) }}" target="_blank" class="text-blue-600 underline">View</a>
                                    @else
                                        <span class="text-gray-400">No document</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.thesis.edit', $thesis->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>

                                    <form action="{{ route('admin.thesis.destroy', $thesis->id) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this thesis?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($theses->isEmpty())
                    <p class="mt-4 text-gray-500">No thesis records found.</p>
                @endif

                <div class="mt-6 flex justify-center">
                    {{ $theses->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
