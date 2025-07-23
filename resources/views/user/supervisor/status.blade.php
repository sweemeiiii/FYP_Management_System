<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Status From Supervisor') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-semibold mb-4">Registration Status</h2>

        @if($registration->count())
            @foreach($registration as $registration)
                <div class="mb-6 p-4 border rounded bg-gray-50">
                    <p><strong>Supervisor:</strong> {{ $registration->supervisor->name ?? 'Not Assigned' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="@if($registration->status == 'approved') text-green-600 
                                    @elseif($registration->status == 'rejected') text-red-600 
                                    @else text-yellow-500 @endif font-bold">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </p>

                    <p><strong>
                        @if($registration->status === 'pending')
                            Registered At:
                            {{ \Carbon\Carbon::parse($registration->created_at)->format('d M Y, h:i A') }}
                        @elseif($registration->status === 'approved')
                            Approved At:
                            {{ \Carbon\Carbon::parse($registration->updated_at)->format('d M Y, h:i A') }}
                        @elseif($registration->status === 'rejected')
                            Rejected At:
                            {{ \Carbon\Carbon::parse($registration->updated_at)->format('d M Y, h:i A') }}
                        @endif
                    </strong></p>
                </div>
            @endforeach
        @else
            <p class="text-gray-600">You have not registered with any supervisor yet.</p>
        @endif
    </div>
</x-app-layout>
