<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Manage Supervisor') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mx-6 mt-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="py-12">        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Supervisor List</h3>
                <a href="{{ route('admin.supervisor.create') }}" class="bg-blue-500 text-black px-4 py-2 rounded mb-4 inline-block">
                    + Add Supervisor
                </a>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th> --}}
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($supervisors as $supervisor)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $supervisor->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $supervisor->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $supervisor->department }}</td>
                            {{-- <td class="px-6 py-4 whitespace-nowrap">********</td> --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.supervisor.edit', $supervisor->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                <form action="{{ route('admin.supervisor.destroy', $supervisor->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-center">
                {{ $supervisors->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
