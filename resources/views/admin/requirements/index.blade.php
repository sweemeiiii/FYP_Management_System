<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Manage Document Submission Deadline') }}
        </h2>
    </x-slot>
    
    <div class="py-4 px-6">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6">
            <a href="{{ route('admin.requirements.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mb-4 inline-block">+ Add Documents</a>
            <table class="min-w-full table-auto border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">Title</th>
                        <th class="px-4 py-2 border">Deadline</th>
                        <th class="px-4 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requirements as $requirement)
                        <tr>
                            <td class="px-4 py-2 border">{{ $requirement->title }}</td>
                            <td class="px-4 py-2 border">
                                <form action="{{ route('admin.requirements.update', $requirement->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')
                                    <input 
                                        type="datetime-local" 
                                        name="due_date" 
                                        value="{{ optional($requirement->due_date)->format('Y-m-d\TH:i') }}" 
                                        class="border p-1 rounded">
                                    <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600" title="Edit">
                                        ✏️
                                    </button>
                                </form>
                                

                            </td>
                            <td class="px-4 py-2 border">
                                @if ($requirement->due_date)
                                    <span class="text-sm text-gray-600">Currently: {{ $requirement->due_date->format('d M Y') }}</span>
                                @else
                                    <span class="text-sm text-red-500">Not Set</span>
                                @endif
                                <form action="{{ route('admin.requirements.destroy', $requirement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this requirement?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" title="Delete">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
