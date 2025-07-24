<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Add New Document') }}
        </h2>
    </x-slot>

    <div class="py-4 px-6">
        <div class="bg-white shadow rounded-lg p-6 max-w-md mx-auto">
            <form method="POST" action="{{ route('admin.requirements.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Document Title</label>
                    <select name="title" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Select Title --</option>
                        <option value="Proposal">Proposal</option>
                        <option value="SRS">SRS</option>
                        <option value="Final Report">Final Report</option>
                        <option value="Presentation Slide">Presentation Slide</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Deadline</label>
                    <input type="datetime-local" name="due_date" class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('admin.requirements.index') }}" class="text-gray-500 hover:underline">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
