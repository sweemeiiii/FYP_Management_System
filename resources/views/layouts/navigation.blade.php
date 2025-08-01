<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                @php
                    $usertype = Auth::user()->usertype;
                @endphp

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link> --}}

                    @if($usertype === 'admin')

                        <x-nav-link :href="route('admin.student.index')" :active="request()->routeIs('admin.student')">
                            {{ __('Manage Student') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.supervisor.index')" :active="request()->routeIs('admin.supervisor.index')">
                            {{ __('Manage Supervisor') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.thesis.index')" :active="request()->routeIs('admin.thesis.index')">
                            {{ __('Manage Thesis') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.student_progress.index')" :active="request()->routeIs('admin.student_progress.index')">
                            {{ __('Student Progress') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.requirements.index')" :active="request()->routeIs('admin.student_progress.index')">
                            {{ __('Doc Deadline') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.reports.filter')" :active="request()->routeIs('admin.reports.filter')">
                            {{ __('Report') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.announcements.index')" :active="request()->routeIs('admin.announcements.index')">
                            {{ __('Announcement Board') }}
                        </x-nav-link>

                        <x-nav-link :href="url('/chatify')" :active="request()->is('chatify')"> 
                            {{ __('Chat') }} 
                        </x-nav-link>

                    @elseif ($usertype === 'supervisor')
                        <x-nav-link :href="route('supervisor.approval')" :active="request()->routeIs('supervisor.approval')">
                            {{ __('Approve Registration') }}
                        </x-nav-link>

                        <x-nav-link :href="route('supervisor.calendar.index')" :active="request()->routeIs('supervisor.calendar.index')">
                            {{ __('Calendar') }}
                        </x-nav-link>

                        <x-nav-link :href="route('supervisor.student_progress.index')" :active="request()->routeIs('supervisor.student_progress.index')">
                            {{ __('Student Progress') }}
                        </x-nav-link>

                        <x-nav-link :href="url('/chatify')" :active="request()->is('chatify')"> 
                            {{ __('Chat') }} 
                        </x-nav-link>

                    @elseif ($usertype === 'user')
                        <x-nav-link :href="route('user.supervisor.index')" :active="request()->routeIs('user.supervisor.index')">
                            {{ __('Available Supervisor') }}
                        </x-nav-link>

                        <x-nav-link :href="route('user.supervisor.status')" :active="request()->routeIs('user.supervisor.status')">
                            {{ __('Supervisor Status') }}
                        </x-nav-link>

                        <x-nav-link :href="route('user.thesis.index')" :active="request()->routeIs('user.thesis.index')">
                            {{ __('Search Thesis') }}
                        </x-nav-link>

                        <x-nav-link :href="route('user.documents.index')" :active="request()->routeIs('user.documents.index')">
                            {{ __('Submission') }}
                        </x-nav-link>

                        <x-nav-link :href="route('user.calendar.index')" :active="request()->routeIs('user.calendar.index')">
                            {{ __('Calendar') }}
                        </x-nav-link>

                        <x-nav-link :href="route('user.notifications.index')" :active="request()->routeIs('user.notifications.index')">
                            {{ __('Notice Board') }}
                        </x-nav-link>

                        <x-nav-link :href="url('/chatify')" :active="request()->is('chatify')"> 
                            {{ __('Chat') }} 
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
