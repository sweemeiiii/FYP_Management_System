<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Student Registration Approvals') }}
        </h2>
        <div class="max-w-6xl mx-auto mt-6 px-4">
            <form method="GET" action="{{ route('supervisor.approval') }}" class="flex flex-wrap items-end gap-4 bg-white p-4 rounded shadow">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Year</label>
                    <input type="text" name="year" value="{{ request('year') }}" placeholder="e.g. 2025"
                        class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">FYP</label>
                    <select name="semester"
                        class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Select --</option>
                        <option value="Semester 1" {{ request('semester') == 'Semester 1' ? 'selected' : '' }}>FYP 1</option>
                        <option value="Semester 2" {{ request('semester') == 'Semester 2' ? 'selected' : '' }}>FYP 2</option>
                    </select>
                </div>

                <div class="pt-4 flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Filter
                    </button>
                    <a href="{{ route('supervisor.approval') }}" class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto mt-10 p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Pending Registrations</h2>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($registrations->isEmpty())
            <p class="text-gray-600">No pending registration requests.</p>
        @else
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2">Student ID</th>
                        <th class="px-4 py-2">Student Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">FYP</th>
                        <th class="px-4 py-2">Registered At</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $registration)
                        @php
                            $fypLabel = $registration->semester === 'Semester 1' ? 'FYP1' : 'FYP2';
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $registration->student->student_id }}</td>
                            <td class="px-4 py-2">{{ $registration->student->name }}</td>
                            <td class="px-4 py-2">{{ $registration->student->email }}</td>
                            <td class="px-4 py-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $fypLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($registration->created_at)->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('supervisor.approval.update', $registration->id) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">✔️</button>
                                </form>

                                <form method="POST" action="{{ route('supervisor.approval.update', $registration->id) }}" class="inline ml-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">✖</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="max-w-6xl mx-auto mt-10 p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Approved Students</h2>

        @if($approvedRegistrations->isEmpty())
            <p class="text-gray-600">No approved students yet.</p>
        @else
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2">Student ID</th>
                        <th class="px-4 py-2">Student Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">FYP</th>
                        <th class="px-4 py-2">Approved At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvedRegistrations as $registration)
                        @php
                            $fypLabel = $registration->semester === 'Semester 1' ? 'FYP1' : 'FYP2';
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $registration->student->student_id }}</td>
                            <td class="px-4 py-2">{{ $registration->student->name }}</td>
                            <td class="px-4 py-2">{{ $registration->student->email }}</td>
                            <td class="px-4 py-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $fypLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($registration->updated_at)->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="max-w-6xl mx-auto mt-10 p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Rejected Students</h2>

        @if($rejectedRegistrations->isEmpty())
            <p class="text-gray-600">No rejected students yet.</p>
        @else
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2">Student ID</th>
                        <th class="px-4 py-2">Student Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">FYP</th>
                        <th class="px-4 py-2">Rejected At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rejectedRegistrations as $registration)
                        @php
                            $fypLabel = $registration->semester === 'Semester 1' ? 'FYP1' : 'FYP2';
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $registration->student->student_id }}</td>
                            <td class="px-4 py-2">{{ $registration->student->name }}</td>
                            <td class="px-4 py-2">{{ $registration->student->email }}</td>
                            <td class="px-4 py-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $fypLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($registration->updated_at)->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</x-app-layout>
