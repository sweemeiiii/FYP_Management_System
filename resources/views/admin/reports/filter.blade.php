<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Generate Supervisor-Student Report
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <form action="{{ route('admin.reports.registration') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Select Year</label>
                <select name="year" class="form-select w-full rounded">
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Select FYP</label>
                <select name="semester" class="form-select w-full rounded">
                    <option value="">---FYP---</option>
                    <option value="Semester 1">FYP 1</option>
                    <option value="Semester 2">FYP 2</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Select Supervisor</label>
                <select name="supervisor_id" class="form-select w-full rounded">
                    <option value="">---All Supervisors---</option>
                    @foreach($supervisors as $supervisor)
                        <option value="{{ $supervisor->id }}">{{ $supervisor->name }} ({{ $supervisor->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Download PDF
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
