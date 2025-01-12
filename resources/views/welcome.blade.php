<x-guest-layout>
    <header class="header my-8">
        <div class="container px-4 sm:px-8 lg:px-16 xl:px-20 mx-auto">
            <div class="header-wrapper flex items-center justify-between">
                <div class="header-logo">
                    <h1 class="font-semibold text-black leading-relaxed"><a href="">
                            {{ config('app.name') }}</a></h1>
                </div>

                <!-- Navbar -->
                <navbar class="navbar ">
                    <ul class="flex space-x-8 text-sm font-semibold">
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="cta bg-blue-500 border-b-4 border-blue-700 hover:bg-blue-600 px-3 py-2 rounded text-white font-normal">
                            Get started </a></li>
                    </ul>
                </navbar>

            </div>
        </div>

    </header>
    <div class="hero bg-gray-100 py-16 min-h-screen flex items-center justify-center ">
        <div class="container px-4 sm:px-8 lg:px-16 xl:px-20 mx-auto">

            <div class="hero-wrapper grid grid-cols-1 md:grid-cols-12 gap-8 items-center">

                <div class="hero-text col-span-6">
                    <h1 class=" font-bold text-4xl md:text-5xl max-w-xl text-gray-900 leading-tight">
                        {{ config('app.name') }}</h1>
                    <hr class=" w-12 h-1 bg-orange-500 rounded-full mt-8">
                    <p class="text-gray-800 text-base leading-relaxed mt-8 font-semibold">
                        An application designed to simplify financial management for individuals. This application
                        addresses
                        the challenges of managing income, expenses, and multiple accounts, empowering users to gain
                        full
                        control over their finances. With its advanced features and user-friendly interface.
                    </p>
                    <div class="get-app flex space-x-5 mt-10 justify-center md:justify-start">
                        @if (Route::has('login'))
                            @auth
                                <div class="flex gap-4 flex-1">
                                    <a href="{{ route('dashboard') }}"
                                        class="inline-flex items-center justify-center px-3 py-1 text-base font-medium text-center text-gray-900 border-b-4  border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                        Dashboard
                                    </a>
                                    <form action="{{ route('logout') }}" method="post">
                                        @csrf
                                        <x-danger-button type="submit">
                                            Logout
                                        </x-danger-button>
                                    </form>

                                </div>
                            @else
                                <div class="flex gap-4  flex-1">

                                    <a href="{{ route('login') }}" wire:navigate
                                        class="inline-flex items-center justify-center    px-3 py-2 text-base font-medium text-center  border-b-4  border rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                                        Login
                                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('register') }}" wire:navigate
                                        class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center border-b-4  text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                        Create new account
                                    </a>
                                </div>
                            @endauth
                        @endif
                    </div>
                </div>

                <div class="hero-image col-span-6 flex justify-center">
                    <img src="{{ asset('wallet-svgrepo-com.png') }}"
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
