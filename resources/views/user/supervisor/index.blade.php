<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Available Supervisors') }}
        </h2>
    </x-slot>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($groupedSupervisors as $department => $supervisors)
            <div class="card text-black p-4 flex flex-col items-center">
                {{ strtoupper($department) }}</div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    @foreach ($supervisors as $supervisor)
                        <div class="bg-white-600 text-black rounded-lg shadow-md p-4 flex flex-col items-center">
                            <div class="text-lg font-semibold">{{ $supervisor->name }}</div>
                            <div class="text-sm mb-2">{{ $supervisor->email }}</div>
                            <a href="{{ route('supervisor.register', $supervisor->id) }}"  
                                class="mt-auto bg-cyan-500 text-black px-4 py-2 rounded hover:bg-cyan-600">
                                REGISTER NOW
                             </a>
                             
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
