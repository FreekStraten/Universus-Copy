<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <div class="hidden dark:flex">
                                <img src="{{ asset('images/universusLogo.png') }}" alt="{{ __('messages.ToHome') }}" class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200">
                        </div>
                        <div class="flex dark:hidden">
                                <img src="{{ asset('images/universusLogo_white_mode.png') }}" alt="{{ __('messages.ToHome') }}" class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200">
                        </div>
                    </a>

                </div>
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @foreach($navbar as $navitem)
                        @if(!$navitem->dropdownVisible)
                            <x-nav-link :href="route($navitem->url)" :active="request()->routeIs($navitem->url)">
                                {{ __($navitem->name) }}
                            </x-nav-link>
                        @else
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 mt-4 ml-2 mr-2">
                                        <div>{{ __($navitem->name) }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    @foreach($navitem->dropdown as $dropdown)
                                        <x-dropdown-link href="{{ route($dropdown->url) }}">
                                            {{ __($dropdown->name) }}
                                        </x-dropdown-link>
                                    @endforeach
                                </x-slot>
                            </x-dropdown>
                        @endif
                    @endforeach
                </div>


{{--                <x-dropdown align="left" width="48">--}}
{{--                    <x-slot name="trigger">--}}
{{--                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 mt-4 ml-2 mr-2">--}}
{{--                            <div>{{ __('messages.Language') }}</div>--}}
{{--                            <div class="ml-1">--}}
{{--                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">--}}
{{--                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />--}}
{{--                                </svg>--}}
{{--                            </div>--}}
{{--                        </button>--}}
{{--                    </x-slot>--}}
{{--                    <x-slot name="content">--}}
{{--                        <x-dropdown-link :href="route('locale', ['locale' => 'en'])">--}}
{{--                            {{ __('messages.English') }}--}}
{{--                        </x-dropdown-link>--}}
{{--                        <x-dropdown-link :href="route('locale', ['locale' => 'nl'])">--}}
{{--                            {{ __('messages.Dutch') }}--}}
{{--                        </x-dropdown-link>--}}
{{--                    </x-slot>--}}
{{--                </x-dropdown>--}}
            </div>


            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div> <!-- Notifications dropdown -->
                    @if(!is_null(Auth::user()))
                        <x-dropdown align="right" width="w-96">
                            <x-slot name="trigger">
                                <button id="notifications_open_button" class=" inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div class="">
                                        <span class="relative inline-block">
                                          <i class="fa fa-bell"></i>
                                            @if($notifications->amountUnread > 0)
                                                <span id="notification_amount_popup_bubble" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full w-3/4 h-3/4">{{$notifications->amountUnread}}</span>
                                            @endif
                                        </span>

                                    </div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @if($notifications->isEmpty())
                                    <div class="align-center align-middle dark:text-white m-2">
                                    <span class="p-2">
                                        {{ __('notification.NoNotifications') }}
                                    </span>
                                    </div>
                                @endif

                                @foreach($notifications as $notification)
                                    <!-- if unread, show a red dot next to it -->
                                    @if($notification->read)
                                        <div class="align-center align-middle dark:text-white grid grid-cols-1 m-1">
                                            <div class="col-span-1 text-center ">
                                                @else
                                                    <div class="align-center align-middle dark:text-white grid grid-cols-12 m-1">
                                                        <div class="flex items-center justify-center border-r border-gray-600 m-1">
                                                            <span class="inline-block w-2 h-2 mr-2 bg-red-600 rounded-full "></span>
                                                        </div>
                                                        <div class="col-span-11 text-center ">
                                                            @endif
                                                <p class="p-2 text-lg">{{ $notification->title  }}</p>
                                                <p class="p-2">{{ $notification->body  }}</p>
                                                <div class="m-1">
                                                    <p class="text-sm">{{$notification->created_at->diffForHumans()}}</p>
                                                </div>
                                                <div class="flex justify-center items-center">
                                                    <div class="flex justify-center items-center w-1/2 border-b border-gray-600">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                @endforeach
                            </x-slot>
                        </x-dropdown>
                    @endif
                </div>

                @if(!is_null(Auth::user()))
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if(!is_null(Auth::user()))
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Login.Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Login.LogOut') }}
                                    </x-dropdown-link>
                                </form>
                            @else
                                <x-dropdown-link :href="route('login')">
                                    {{ __('Login.Login') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('register')">
                                    {{ __('Login.Register') }}
                                </x-dropdown-link>
                            @endif
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center justify-center">
                        <a href="{{ route('login') }}">{{ __('Login.Login') }}</a>
                        <a href="{{ route('register') }}" class="inline-block px-4 py-2 leading-none text-white bg-blue-500 hover:bg-blue-600 rounded font-semibold ml-2">{{ __('Login.Register') }}</a>
                    </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button aria-label="open/close hamburger menu" @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
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
{{--            <x-responsive-nav-link :href="route('wedstrijden')" :active="request()->routeIs('wedstrijden')">--}}
{{--                {{ __('CompetitionDetails') }}--}}
{{--            </x-responsive-nav-link>--}}
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
{{--                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>--}}
{{--                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>--}}
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Login.Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Login.LogOut') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
