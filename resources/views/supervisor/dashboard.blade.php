<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supervisor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-2">Total Students</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $studentCount }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow col-span-1 md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">Upcoming Document Deadlines</h3>
                <ul class="list-disc ml-5 space-y-2">
                    @forelse ($upcomingDocuments as $doc)
                        <li>
                            <span class="font-medium">{{ $doc->title }}</span> — 
                            <span class="text-gray-600">{{ \Carbon\Carbon::parse($doc->due_date)->format('d M Y') }}</span>
                        </li>
                    @empty
                        <li>No upcoming documents.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
