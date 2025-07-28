<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-yellow-100 p-4 rounded-xl shadow-md mb-10">
            <h3 class="text-lg font-bold mb-3 text-yellow-800">📒 Document Deadlines</h3>
            @if ($documentRequirements->count())
                <ul class="list-disc list-inside text-sm text-yellow-900 space-y-1">
                    @foreach ($documentRequirements as $doc)
                        <li>
                            <strong>{{ $doc->title }}</strong> – Due: {{ \Carbon\Carbon::parse($doc->due_date)->format('d M Y') }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-600">No document requirements at the moment.</p>
            @endif
        </div>

        {{-- Upcoming Events --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Upcoming Events</h3>
            @if($upcomingEvents->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($upcomingEvents as $event)
                        <li class="py-2">
                            <div class="font-medium text-gray-800">{{ $event->title }}</div>
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->start)->format('d M Y, h:i A') }}</div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No upcoming events found.</p>
            @endif
        </div>

        {{-- Progress Overview --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Progress Overview</h3>

            @if ($totalDocuments > 0)
                <div class="mb-2 text-sm text-gray-600">
                    Submitted: <span class="font-semibold">{{ $submittedDocuments }}</span> / {{ $totalDocuments }}
                </div>

                {{-- Progress Bar --}}
                <div class="w-full bg-gray-200 rounded-full h-4 mb-3">
                    <div class="bg-green-500 h-4 rounded-full transition-all"
                        style="width: {{ ($submittedDocuments / $totalDocuments) * 100 }}%;">
                    </div>
                </div>

                <p class="text-sm text-gray-500">
                    Last updated:
                    {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->format('d M Y') : 'No submission yet' }}
                </p>
            @else
                <p class="text-gray-500">No document requirements assigned yet.</p>
            @endif
        </div>

        {{-- Registration Status --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Registration Status</h3>
            @if($registration)
                <p>Status: <span class="font-semibold text-blue-600">{{ ucfirst($registration->status) }}</span></p>
                <p>Semester: {{ $registration->semester }}, Year: {{ $registration->year }}</p>
            @else
                <p class="text-gray-500">You have not registered for supervision yet.</p>
            @endif
        </div>

        {{-- Supervisor Info --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Assigned Supervisor</h3>
            @if($supervisor)
                <p>Name: <span class="font-semibold">{{ $supervisor->name }}</span></p>
                <p>Email: {{ $supervisor->email }}</p>
            @else
                <p class="text-gray-500">No supervisor assigned yet.</p>
            @endif
        </div>

        


    </div>
</x-app-layout>
