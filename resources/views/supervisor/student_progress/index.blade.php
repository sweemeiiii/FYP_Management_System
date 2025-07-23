<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('View Student Progress') }}
        </h2>
    </x-slot>

    <div class="py-4 px-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Student Progress Overview</h3>
            <form method="GET" class="mb-4 flex space-x-4">
                <div>
                    <label for="year" class="block text-sm font-medium">Year</label>
                    <input type="text" name="year" id="year" value="{{ request('year') }}" class="form-input rounded">
                </div>

                <div>
                    <label for="semester" class="block text-sm font-medium">FYP</label>
                    <select name="semester" id="semester" class="form-select rounded">
                        <option value="">-- Select FYP --</option>
                        <option value="Semester 1" {{ request('semester') == 'Semester 1' ? 'selected' : '' }}>FYP 1</option>
                        <option value="Semester 2" {{ request('semester') == 'Semester 2' ? 'selected' : '' }}>FYP 2</option>
                    </select>
                </div>

                <div class="pt-4 flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                    <a href="{{ route('supervisor.student_progress.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Reset</a>
                </div>
            </form>

            <table class="min-w-full table-auto border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">Student ID</th>
                        <th class="px-4 py-2 border">Student Name</th>
                        <th class="px-4 py-2 border">FYP</th>
                        @foreach ($requiredDocuments as $doc)
                            <th class="px-4 py-2 border">{{ $doc }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($progressData as $data)
                        <tr>
                            <td class="px-4 py-2 border">{{ $data['student']->student_id ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border">{{ $data['student']->name }}</td>
                            
                            @php
                                $fyp1 = $data['student']->registrations->where('semester', 'Semester 1')->first();
                                $fyp2 = $data['student']->registrations->where('semester', 'Semester 2')->first();

                                if ($fyp1) {
                                    $fypLabel = 'FYP1 (' . $fyp1->year . ')';
                                    $badgeClasses = 'bg-blue-100 text-blue-800';
                                } elseif ($fyp2) {
                                    $fypLabel = 'FYP2 (' . $fyp2->year . ')';
                                    $badgeClasses = 'bg-purple-100 text-purple-800';
                                } else {
                                    $fypLabel = 'N/A';
                                    $badgeClasses = 'bg-gray-100 text-gray-600';
                                }
                            @endphp

                            <td class="px-4 py-2 border text-center">
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded inline-block {{ $badgeClasses }}">
                                    {{ $fypLabel }}
                                </span>
                            </td>

                            @foreach ($requiredDocuments as $doc)
                                <td class="px-4 py-2 border text-center">
                                    @php
                                        $docData = $data['documents'][$doc];
                                    @endphp

                                    @if ($docData['document'])
                                        <div class="flex flex-col items-center space-y-1">
                                            <a href="{{ asset('storage/' . $docData['document']->file_path) }}" target="_blank" class="bg-blue-200 text-blue-600 px-2 py-1 rounded text-s hover:bg-blue-200">View</a>
                                            <a href="{{ route('supervisor.document.download', $docData['document']->id) }}" class="bg-green-200 text-green-600 px-2 py-1 rounded text-s hover:bg-green-200">Download</a>
                                            @php
                                                $timestamp = $docData['document']->submitted_at ?? $docData['document']->created_at;
                                            @endphp

                                            <span class="text-sm {{ $docData['document']->is_late ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                                {{ $docData['document']->is_late ? '⚠️ Submitted late on:' : '✅ Submitted on:' }}
                                                {{ \Carbon\Carbon::parse($timestamp)->format('Y-m-d H:i') }}
                                            </span>

                                        </div>
                                    @else
                                        <div class="flex flex-col items-center space-y-1">
                                            @if ($docData['status'] === 'overdue')
                                                <span class="text-sm">❌</span>
                                                <span class="text-red-600 text-sm">Not Available (Due: {{ \Carbon\Carbon::parse($docData['due'])->format('Y-m-d') }})</span>
                                            @else
                                                <span class="text-xl">🕒</span>
                                                <span class="text-yellow-500 text-sm">Pending (Due: {{ \Carbon\Carbon::parse($docData['due'])->format('Y-m-d') }})</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
