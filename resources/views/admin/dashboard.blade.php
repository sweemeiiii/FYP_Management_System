<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow text-center">
                    <h3 class="text-lg font-medium text-gray-700">Total Users</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $userCount }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow text-center">
                    <h3 class="text-lg font-medium text-gray-700">Students</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $studentCount }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow text-center">
                    <h3 class="text-lg font-medium text-gray-700">Supervisors</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $supervisorCount }}</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow text-center">
                    <h3 class="text-lg font-medium text-gray-700">Upcoming Documents</h3>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $upcomingDocuments->count() }}</p>
                </div>
            </div>

            {{-- Quick Access Buttons --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                <a href="{{ route('admin.student.index') }}" class="block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 rounded-xl text-center shadow-md transition">
                    Manage Students
                </a>
                <a href="{{ route('admin.supervisor.index') }}" class="block bg-green-500 hover:bg-green-600 text-white font-semibold py-4 rounded-xl text-center shadow-md transition">
                    Manage Supervisors
                </a>
                <a href="{{ route('admin.requirements.index') }}" class="block bg-purple-500 hover:bg-purple-600 text-white font-semibold py-4 rounded-xl text-center shadow-md transition">
                    Document Deadlines
                </a>
            
            </div>

            {{-- Upcoming Document Requirements --}}
                <div class="bg-white rounded-xl shadow p-6 mb-10">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Upcoming Document Deadlines</h3>
                    @if($upcomingDocuments->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($upcomingDocuments as $doc)
                                <li class="py-4">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="text-base font-medium text-gray-800">{{ $doc->title }}</div>
                                            <div class="text-sm text-gray-500">
                                                Due: {{ \Carbon\Carbon::parse($doc->due_date)->format('d M Y, h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">No upcoming document due dates found.</p>
                    @endif
                </div>

            {{-- Recent Users
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Users</h3>
                @if($recentUsers->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($recentUsers as $user)
                            <li class="py-4 flex justify-between">
                                <div>
                                    <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                                <div class="text-sm text-gray-400">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No recent users found.</p>
                @endif
            </div> --}}

        </div>
    </div>
</x-app-layout>
