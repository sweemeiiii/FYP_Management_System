<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Edit Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('user.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700">Document Title</label>
                        <input type="text" name="documentTitle" value="{{ old('documentTitle', $document->title) }}"
                            class="w-full border rounded px-3 py-2 bg-gray-100 cursor-not-allowed" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Replace PDF File (Optional)</label>
                        <input type="file" name="documentFile" class="w-full">
                        <p class="text-sm text-gray-500 mt-1">Leave empty if you do not want to replace the file.</p>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Update 
                        </button>
                        <a href="{{ route('user.documents.index') }}" 
                            class="inline-flex items-center px-6 py-2 bg-gray-400 text-black border border-gray-600 rounded shadow-sm hover:bg-gray-500">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
