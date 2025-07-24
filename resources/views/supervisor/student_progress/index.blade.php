<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('View Student Progress') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Tabs for FYP1 and FYP2 --}}
        <div class="mb-6 flex space-x-4">
            <a href="{{ route('supervisor.student_progress.fyp1') }}"
               class="{{ request()->is('supervisor/student-progress/fyp1') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }} px-4 py-2 rounded hover:bg-blue-500 hover:text-white transition">
                FYP1
            </a>
            <a href="{{ route('supervisor.student_progress.fyp2') }}"
               class="{{ request()->is('supervisor/student-progress/fyp2') ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-800' }} px-4 py-2 rounded hover:bg-purple-500 hover:text-white transition">
                FYP2
            </a>
            <div class="bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded hover:bg-yellow-500 hover:text-white transition inline-block">
                Total Students:
                <span class="text-blue-600 font-bold group-hover:text-white">{{ $totalStudents }}</span>
            </div>
        </div>

        {{-- Filter Form --}}
        <form method="GET" class="mb-6 bg-white p-4 rounded shadow flex flex-wrap gap-4 items-center">
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                <select name="year" id="year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All</option>
                    @for ($y = now()->year; $y >= 2025; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Filter
                </button>
                <a href="{{ route(Route::currentRouteName()) }}"
                   class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Reset
                </a>
            </div>
        </form>

        {{-- Student Progress Table --}}
        <div class="bg-white shadow rounded p-6">
            <h3 class="text-lg font-semibold mb-4">Student Progress Overview</h3>

            <table class="min-w-full table-auto text-sm border">
                <thead class="bg-gray-100">
                    <tr class="text-left">
                        <th class="px-4 py-2 border">Student ID</th>
                        <th class="px-4 py-2 border">Student Name</th>
                        <th class="px-4 py-2 border">FYP</th>
                        @foreach($requiredDocuments as $doc)
                            <th class="px-4 py-2 border">{{ $doc }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        @php
                            $registration = $student->registration
                            ->where('semester', $semester)
                            ->first();;
                        @endphp

                        @if ($registration)
                            @php
                                $fypLabel = ($registration->semester === 'Semester 1' ? 'FYP1' : 'FYP2') . ' (' . $registration->year . ')';
                                $badgeClasses = $registration->semester === 'Semester 1'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-purple-100 text-purple-800';
                                $submittedDocs = $student->documents;
                            @endphp

                            <tr>
                                <td class="px-4 py-2 border">{{ $student->student_id ?? 'N/A' }}</td>
                                <td class="px-4 py-2 border">{{ $student->name }}</td>
                                <td class="px-4 py-2 border text-center">
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded inline-block {{ $badgeClasses }}">
                                        {{ $fypLabel }}
                                    </span>
                                </td>

                                {{-- Document columns --}}
                                @foreach ($requiredDocuments as $doc)
                                    @php
                                        $docKey = Str::lower(Str::replace(' ', '', $doc));
                                        $matchedDoc = null;
                                        foreach ($submittedDocs as $submittedDoc) {
                                            $titleKey = Str::lower(Str::replace([' ', '_', '-'], '', $submittedDoc->title));
                                            if (Str::contains($titleKey, $docKey)) {
                                                $matchedDoc = $submittedDoc;
                                                break;
                                            }
                                        }

                                        $due = optional($requirements[$doc])->due_date;
                                        $timestamp = $matchedDoc?->created_at;
                                        $isLate = $matchedDoc && $due && $timestamp->gt($due);
                                    @endphp
                                    <td class="px-4 py-2 border text-center">
                                        @if ($matchedDoc)
                                            <a href="{{ route('supervisor.document.download', $matchedDoc->id) }}"
                                            class="underline text-blue-600 hover:text-blue-800">
                                                Download
                                            </a>
                                            <div class="mt-1 text-xs {{ $isLate ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                                {{ $isLate ? '⚠️ Submitted late on:' : '✅ Submitted on:' }}
                                                {{ $timestamp->format('Y-m-d H:i') }}
                                            </div>
                                        @else
                                            <span class="text-yellow-600">Pending</span>
                                        @endif

                                        @if ($due)
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                📅 Due: {{ \Carbon\Carbon::parse($due)->format('d M Y') }}
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="{{ 3 + count($requiredDocuments) }}" class="px-4 py-4 text-center text-gray-500">
                                No student data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
            <div class="mt-6">
                {{ $students->links() }}
            </div>

    </div>
</x-app-layout>
