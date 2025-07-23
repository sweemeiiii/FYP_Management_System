<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Upload Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                @if(auth()->user()->usertype === 'user')
                    @if(session('success'))
                        <div class="mb-4 text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($dueDate)
                        <p class="text-sm text-gray-600 mb-4">Due Date: {{ \Carbon\Carbon::parse($dueDate)->format('d M Y, h:i A') }}</p>
                    @endif

                    @if(!$dueDate || now()->lessThan($dueDate))
                        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Document Title</label>
                                <input type="text" name="documentTitle" class="w-full border-gray-300 rounded px-4 py-2 shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Choose File</label>
                                <input type="file" name="documentFile" class="w-full border-gray-900 rounded px-4 py-2 shadow-sm" required>
                            </div>

                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-gray-400 text-black border border-gray-600 rounded shadow-sm hover:bg-gray-500">
                                + Upload
                            </button>

                            <a href="{{ route('user.documents.index') }}"
                            class="inline-flex items-center px-6 py-2 bg-gray-400 text-black border border-gray-600 rounded shadow-sm hover:bg-gray-500">
                                Cancel
                            </a>                        
                        </form>
                    @else
                        <p class="text-red-600 font-semibold">The upload period has ended. You can no longer submit this document.</p>
                    @endif

                @else
                    <p class="text-gray-600">You do not have permission to upload documents.</p>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
