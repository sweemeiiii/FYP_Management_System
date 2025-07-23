<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('View Student Progress') }}
        </h2>
    </x-slot>

    <div class="py-4 px-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Student Progress Overview</h3>
            <form method="GET" action="{{ route('admin.student_progress.index') }}" class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Year</label>
                    <input type="number" name="year" value="{{ request('year') }}" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">FYP</label>
                    <select name="semester" class="form-select">
                        <option value="">-- Select FYP --</option>
                        <option value="Semester 1" {{ request('semester') == 'Semester 1' ? 'selected' : '' }}>FYP 1</option>
                        <option value="Semester 2" {{ request('semester') == 'Semester 2' ? 'selected' : '' }}>FYP 2</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Supervisor</label>
                    <select name="supervisor_id" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">-- Select Supervisor --</option>
                        @foreach ($supervisors as $supervisor)
                            <option value="{{ $supervisor->id }}" {{ request('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                {{ $supervisor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4 flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                        <a href="{{ route('admin.student_progress.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Reset</a>
                    </div>

                    <div class="text-gray-700 font-medium">
                        Total Students: <span class="text-blue-600 font-bold"> {{ $totalStudents }}
                    </div>
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
                    @foreach ($studentsPaginated as $student)

                        <tr>
                            <td class="px-4 py-2 border">{{ $student->student_id ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border">{{ $student->name }}</td>
                           {{-- @php
                                $fyp1Registration = $student->registration()
                                    ->where('semester', 'Semester 1')
                                    ->first();

                                $fypLabel = $fyp1Registration ? 'FYP1' : (optional($student->registration)->semester === 'Semester 2' ? 'FYP2' : 'N/A');
                            @endphp --}}

                            @php
                                $fyp1 = $student->registrations->where('semester', 'Semester 1')->first();
                                $fyp2 = $student->registrations->where('semester', 'Semester 2')->first();

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
                                @php
                                    $docKey = Str::lower(Str::replace(' ', '', $doc));
                                    $matchedDoc = $student->documents->first(function ($submittedDoc) use ($docKey) {
                                        $titleKey = Str::lower(Str::replace([' ', '_', '-'], '', $submittedDoc->title));
                                        return Str::contains($titleKey, $docKey);
                                    });
                                @endphp

                                <td class="px-4 py-2 border text-center">
                                    @php
                                        $requirement = $requirements->firstWhere('title', $doc);
                                        // $now = now();
                                    @endphp

                                    @if ($matchedDoc)
                                        <div class="flex flex-col items-center space-y-1">
                                            <a href="{{ asset('storage/' . $matchedDoc->file_path) }}" target="_blank" class="bg-blue-200 text-blue-600 px-2 py-1 rounded text-s hover:bg-blue-200">View</a>
                                            <a href="{{ route('admin.document.download', $matchedDoc->id) }}" class="bg-green-200 text-green-600 px-2 py-1 rounded text-s hover:bg-green-200">Download</a>
                                            
                                            @php
                                                $timestamp = $matchedDoc->submitted_at ?? $matchedDoc->created_at;
                                            @endphp

                                                <span class="text-xs {{ $matchedDoc->is_late ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                                    {{ $matchedDoc->is_late ? '⚠️ Submitted late on:' : '✅ Submitted on:' }}
                                                    {{ \Carbon\Carbon::parse($timestamp)->format('Y-m-d H:i') }}
                                                </span>
                                        </div>

                                        @elseif ($requirement && $requirement->due_date && now()->lt($requirement->due_date))
                                            <div class="flex flex-col items-center space-y-1 text-yellow-600">
                                                <span class="text-xl">🕒</span>
                                                <span class="text-xs">Pending</span>
                                                <span class="text-xs text-gray-500">Due: {{ optional($requirement->due_date)->format('d M Y') }}</span>
                                            </div>
                                        @else
                                        <div class="flex-col items-center space-y-1">
                                            <span class="text-sm">❌</span>
                                            <span class="text-xs">Not Available</span>
                                            @if ($requirement && $requirement->due_date)
                                                <span class="text-xs text-gray-500">Was due: {{ $requirement->due_date->format('d M Y') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-6 flex justify-center">
                {{ $studentsPaginated->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
