<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Submit Documents') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                @if(auth()->user()->usertype === 'user')

                    {{-- 🔷 Pending Submissions --}}
                    <h2 class="text-xl font-bold text-blue-700 mb-4">📂 Pending Submissions</h2>

                    @foreach($requirements as $requirement)
                        @php
                            $userDoc = $documents->where('title', $requirement->title)->first();
                            $isOverdue = now()->greaterThan($requirement->due_date);
                            $now = now();

                            $currentIndex = $loop->index;
                            $prevRequirement = $requirements[$currentIndex - 1] ?? null;
                            $prevDuePassed = !$prevRequirement || now()->greaterThan($prevRequirement->due_date);

                            // Only show in "Pending" if NOT submitted
                            if ($userDoc) continue;

                            $status = $prevDuePassed ? 'open' : 'locked';

                            $cardBg = match ($status) {
                                'open' => 'bg-yellow-100 border-yellow-400',
                                'locked' => 'bg-gray-100 border-gray-400',
                            };
                        @endphp

                        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 mb-6">
                            <div class="border-l-4 {{ $cardBg }} shadow-sm sm:rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                    Title: {{ $requirement->title }}
                                </h3>

                                <p class="text-sm text-gray-600 mb-4">
                                    Due: {{ \Carbon\Carbon::parse($requirement->due_date)->format('d M Y, h:i A') }}
                                </p>

                                @if (!$prevDuePassed)
                                    <p class="text-yellow-600 font-semibold">🔒 Locked. Please wait until the previous document is due before uploading this one.</p>
                                @else
                                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="documentTitle" value="{{ $requirement->title }}">

                                        <div>
                                            <label class="block text-gray-700 font-semibold mb-2">Choose File</label>
                                            <input type="file" name="documentFile" class="w-full border-gray-300 rounded px-4 py-2 shadow-sm" required>
                                        </div>

                                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gray-400 text-black border border-gray-600 rounded shadow-sm hover:bg-gray-500">
                                            + Upload
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- ✅ Completed Submissions --}}
                    <h2 class="text-xl font-bold text-green-700 mt-10 mb-4">✅ Completed Submissions</h2>

                    @foreach($requirements as $requirement)
                        @php
                            $userDoc = $documents->where('title', $requirement->title)->first();
                            if (!$userDoc) continue; // only show if submitted

                            $cardBg = 'bg-green-100 border-green-400';
                        @endphp

                        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 mb-6">
                            <div class="border-l-4 {{ $cardBg }} shadow-sm sm:rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                    Title: {{ $requirement->title }}
                                </h3>

                                <p class="text-sm text-gray-600 mb-4">
                                    Due: {{ \Carbon\Carbon::parse($requirement->due_date)->format('d M Y, h:i A') }}
                                </p>

                                <div class="flex space-x-4 items-center">
                                    <a href="{{ asset('storage/' . $userDoc->file_path) }}" target="_blank" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">View</a>

                                    @if (!now()->greaterThan($requirement->due_date))
                                        <a href="{{ route('user.documents.edit', $userDoc->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>

                                        <form action="{{ route('user.documents.destroy', $userDoc->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                                        </form>
                                    @endif
                                </div>

                                <div class="mt-2 {{ $userDoc->is_late ? 'text-red-600' : 'text-green-700' }} font-medium">
                                    {{ $userDoc->is_late ? '⚠️ Submitted late on' : '✅ Submitted on' }}
                                    {{ \Carbon\Carbon::parse($userDoc->submitted_at)->format('d M Y, h:i A') }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                @else
                    <p class="text-gray-600">You do not have permission to view uploaded documents.</p>
                @endif

                </div>
            </div>
        </div>
</x-app-layout>
