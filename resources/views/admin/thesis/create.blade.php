<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Upload Thesis') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded p-6">
                <form method="POST" action="{{ route('admin.thesis.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Student Name</label>
                        <input type="text" name="name" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Year</label>
                        <input type="number" name="year" min="2000" max="2100" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Thesis Title</label>
                        <input type="text" name="title" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    </div>

                    {{-- <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Degree Type</label>
                        <select name="type" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="Undergrate">Undergrate Thesis</option>
                            <option value="Master">Master Thesis</option>
                            <option value="Other">Other Thesis</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">University Name</label>
                        <select name="university" class="form-select rounded-md shadow-sm mt-1 block w-full" required>"
                            <option value="University of Wollongong Malaysia">University of Wollongong Malaysia</option>
                        </select>
                    </div> --}}

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Upload Document (PDF)</label>
                        <input type="file" name="document" accept="application/pdf" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    </div>

                    <div class="flex justify-end gap-3 mt-3">
                        <x-primary-button>{{ __('Upload Thesis') }}</x-primary-button>

                        <a href="{{ route('admin.thesis.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-400 text-black border border-gray-600 rounded-md font-semibold text-sm uppercase tracking-wider hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition ease-in-out duration-150">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
