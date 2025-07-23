<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Supervisor') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded p-6">
                <form method="POST" action="{{ route('admin.supervisor.store') }}">
                    @csrf

                    <label for="usertype">User Type:</label>
                    <select name="usertype" id="usertype" class="form-select" required>
                        <option value="user">Student</option>
                        <option value="supervisor">Supervisor</option>
                    </select>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Name</label>
                        <input type="text" name="name" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Email</label>
                        <input type="email" name="email" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Password</label>
                        <input type="password" name="password" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Department</label>
                        <select name="department" required class="form-select rounded-md shadow-sm mt-1 block w-full">
                            <option value="">-- Select Department --</option>
                            <option value="Bachelor of Computer Science (Hons)">Bachelor of Computer Science (Hons)</option>
                            <option value="Bachelor of Software Engineering (Hons)">Bachelor of Software Engineering (Hons)</option>
                            <option value="Bachelor of Information System (Honours) Enterprise Information System">Bachelor of Information System (Honours) Enterprise Information System</option>
                            <option value="Bachelor of Game Development (Honours)">Bachelor of Game Development (Honours)</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-3">
                        <x-primary-button>{{ __('Create Supervisor') }}</x-primary-button>

                        <a href="{{ route('admin.supervisor.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-400 text-black border border-gray-600 rounded-md font-semibold text-sm uppercase tracking-wider hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition ease-in-out duration-150">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
