<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Thesis') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded p-6">
                <form method="POST" action="{{ route('admin.thesis.update', $thesis->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Student Name</label>
                        <input type="text" name="name" value="{{ $thesis->name }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Year</label>
                        <input type="number" name="year" value="{{ $thesis->year }}" min="2000" max="2100" class="form-input rounded-md shadow-sm mt-1 block w-full">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Thesis Title</label>
                        <input type="text" name="title" value="{{ $thesis->title }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    </div>

                    {{-- <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Degree Type</label>
                        <select name="type" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="Undergraduate Thesis" {{ $thesis->type == 'Undergraduate Thesis' ? 'selected' : '' }}>Undergraduate Thesis</option>
                            <option value="Master Thesis" {{ $thesis->type == 'Master Thesis' ? 'selected' : '' }}>Master Thesis</option>
                            <option value="Other" {{ $thesis->type == 'Other' ? 'selected' : '' }}>Other Thesis</option>
                        </select>
                    </div>                     --}}

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Upload Document (PDF)</label>
                        <input type="file" name="document" accept="application/pdf" class="form-input rounded-md shadow-sm mt-1 block w-full" nullable>
                    </div>

                    <div class="flex justify-end gap-3 mt-3">
                        <x-primary-button>{{ __('Update thesis') }}</x-primary-button>

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
