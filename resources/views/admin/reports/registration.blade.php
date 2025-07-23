<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Supervisor & Registered Students Report
        </h2>
    </x-slot>

    <div class="p-6 text-gray-900 text-sm">
        @foreach($registrations as $year => $semesters)
            @foreach($semesters as $semester => $supervisors)
                <h2 class="text-xl font-bold mt-8 mb-2">{{ $year }} - {{ $semester }}</h2>

                @foreach($supervisors as $supervisorId => $items)
                    @php
                        $supervisor = $items->first()->supervisor;
                    @endphp

                    <h3 class="text-lg font-semibold mt-4">Supervisor: {{ $supervisor->name }} ({{ $supervisor->email }})</h3>

                    <div class="overflow-x-auto mt-2">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Student ID</th>
                                    <th class="border px-4 py-2 text-left">Name</th>
                                    <th class="border px-4 py-2 text-left">Email</th>
                                    <th class="border px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $reg)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $reg->student->student_id }}</td>
                                        <td class="border px-4 py-2">{{ $reg->student->name }}</td>
                                        <td class="border px-4 py-2">{{ $reg->student->email }}</td>
                                        <td class="border px-4 py-2 capitalize">{{ $reg->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @endforeach
        @endforeach
    </div>
</x-app-layout>
