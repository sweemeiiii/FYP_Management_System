<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Manage Student') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mx-6 mt-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-6 mt-4">
            <strong>Whoops!</strong> There were some problems with your input:
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="py-12">        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Student List</h3>
                <a href="{{ route('admin.student.create') }}" class="bg-blue-500 text-black px-4 py-2 rounded mb-4 inline-block">
                    + Add Student
                </a>
                {{-- <div class="mt-8 border-t pt-6"> --}}
                    <h2 class="text-lg font-semibold mb-2">OR Upload CSV</h2>
                    <form action="{{ route('admin.student.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Upload CSV File</label>
                            <input type="file" name="csv_file" accept=".csv" required class="form-input rounded-md shadow-sm mt-1 block w-full">
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>{{ __('Upload CSV') }}</x-primary-button>
                        </div>
                    </form>
                </div>

                <form method="POST" action="{{ route('admin.student.multipleSelect') }}">
                    @csrf
                    <div class="mb-4 flex flex-col items-end">
                        <p class="text-l text-gray-500 mb-2">Use these buttons to apply actions to all selected students:</p>
                        <div class="flex justify-end mb-4 space-x-2">
                            <button type="submit" name="action" value="archive"
                                class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 text-sm"
                                title="Archive Selected"
                                onclick="return confirm('Are you sure you want to archive the selected students?')">
                                🗑️ Archive Multiple
                            </button>
                            <button type="submit" name="action" value="graduate"
                                class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 text-sm"
                                title="Graduate Selected"
                                onclick="return confirm('Are you sure you want to graduate selected students?')">
                                🧑🏻‍🎓 Graduate Multiple
                            </button>
                        </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">
                                    <input type="checkbox" id="select-all" class="form-checkbox">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FYP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programme</th>
                                {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th> --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($students as $student)
                                @php
                                    $isArchived = $student->active_status == 2;
                                @endphp
                                <tr class="{{ $isArchived ? 'bg-gray-200 text-gray-600' : '' }}">
                                    <td class="px-4 py-2 text-center">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="form-checkbox row-checkbox">
                                    </td>

                                    <td class="px-4 py-2 text-center">
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

                                        <span class="text-xs font-medium px-2.5 py-0.5 rounded inline-block {{ $badgeClasses }}">
                                            {{ $fypLabel }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">{{ $student->student_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $student->name }}
                                        @if ($isArchived)
                                            <span class="text-xs text-red-500 font-semibold">(Archived)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $student->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap max-w-xs truncate" title="{{ $student->department }}">
                                        {{ $student->department }}
                                    </td>

                                {{-- <td class="px-6 py-4 whitespace-nowrap">********</td> --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.student.edit', $student->id) }}"
                                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm"
                                                title="Edit">
                                                ✏️
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.student.archive', ['id' => $student->id]) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm"
                                                    title="Archive"
                                                    onclick="return confirm('Archive student {{ $student->student_id }}?');">
                                                🗑️
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.student.graduate', $student->id) }}"
                                            onsubmit="return confirm('Mark this student as graduated?');">
                                            @csrf
                                            <button type="submit"
                                                    class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm"
                                                    title="Graduate">
                                                🧑🏻‍🎓
                                            </button>
                                        </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-center">
                {{ $students->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>   
</x-app-layout>
