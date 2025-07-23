<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Register Supervisor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 p-3 bg-yellow-100 text-yellow-700 rounded">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('supervisor.register.store') }}" method="POST">
                @csrf
                <input type="hidden" name="supervisor_id" value="{{ $supervisor->id }}">

                <div class="mb-4">
                    <label class="block text-gray-700">Supervisor</label>
                    <input type="text" value="{{ $supervisor->name }}" disabled class="w-full border-gray-300 rounded mt-1" />
                </div>

                @php
                    $currentYear = now()->year;
                @endphp

                <div class="mb-4">
                    <label for="year" class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <select name="year" required class="form-select mt-1 block w-full">
                        <option value="">-- Select Year --</option>
                        @for ($i = 0; $i < 5; $i++)
                            <option value="{{ $currentYear + $i }}">{{ $currentYear + $i }}</option>
                        @endfor
                    </select>
                </div>

                {{-- <div class="mb-4">
                    <label for="semester" class="block text-sm font-medium text-gray-700">FYP</label>
                    <select name="semester" required class="form-select mt-1 block w-full">
                        <option value="">-- Select FYP --</option>
                        <option value="Semester 1">Semester 1</option>
                        <option value="Semester 2">Semester 2</option>
                    </select>
                </div> --}}

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Submit Registration
                </button>

                <a href="{{ route('user.supervisor.index') }}"
                   class="inline-flex items-center px-6 py-2 bg-gray-400 text-black border border-gray-600 rounded shadow-sm hover:bg-gray-500">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</x-app-layout>
