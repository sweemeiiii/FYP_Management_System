<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Announcements</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6">
        @forelse ($announcements as $announcement)
            <div class="bg-white shadow rounded p-6 mb-4">
                <h3 class="text-lg font-bold text-gray-800">{{ $announcement->title }}</h3>
                <p class="text-sm text-gray-500">{{ $announcement->sent_at->format('d M Y, h:i A') }}</p>
                <p class="mt-3 text-gray-700">{{ $announcement->message }}</p>

                @if ($announcement->document_path)
                    <a href="{{ asset('storage/' . $announcement->document_path) }}" 
                       target="_blank" 
                       class="text-blue-500 underline mt-3 inline-block">
                        View Attachment
                    </a>
                @endif
            </div>
        @empty
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
                No announcements found.
            </div>
        @endforelse
    </div>
</x-app-layout>
