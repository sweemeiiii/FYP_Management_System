<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Create Announcement</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">
        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow p-6 rounded">
            @csrf

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Title</label>
                <input type="text" name="title" class="form-input w-full" required>
            </div>

            <div class="mb-4 relative">
                <label class="block font-medium text-sm text-gray-700">Message</label>
                <textarea name="message" id="message" rows="6" class="form-textarea w-full pr-20" required></textarea>
                
                <div id="word-count" class="absolute bottom-2 right-2 text-sm text-gray-500">
                    0 / 1500 words
                </div>
            </div>


            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Attach Document (optional)</label>
                <input type="file" name="document" class="form-input">
            </div>

            <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Post Announcement</button>

                <a href="{{ route('admin.announcements.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                    Cancel
                </a>
        </form>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const textarea = document.getElementById('message');
                    const wordCountDisplay = document.getElementById('word-count');
                    const maxWords = 1500;

                    textarea.addEventListener('input', () => {
                        const words = textarea.value.trim().split(/\s+/).filter(word => word.length > 0);
                        const count = words.length;
                        wordCountDisplay.textContent = `${count} / ${maxWords} words`;

                        // Optional: prevent typing beyond limit
                        if (count > maxWords) {
                            wordCountDisplay.classList.add('text-red-500');
                        } else {
                            wordCountDisplay.classList.remove('text-red-500');
                        }
                    });
                });
            </script>
            @endpush
    </div>
</x-app-layout>
