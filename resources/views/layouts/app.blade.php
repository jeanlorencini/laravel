<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ config('app.name', 'Sistema de contas de jogos') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
    <nav class="bg-white border-gray-200 px-2 sm:px-4 py-2.5 rounded dark:bg-gray-800">
  <div class="container flex flex-wrap justify-between items-center mx-auto">
    <a href="{{ url('/') }}" class="flex items-center">
        <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">{{ config('app.name', 'Sistema de contas de jogos') }}</span>
    </a>
    <button data-collapse-toggle="navbarSupportedContent" type="button" class="inline-flex items-center p-2 text-sm text-white rounded-lg md:hidden focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-600" aria-controls="navbarSupportedContent" aria-expanded="false">
      <span class="sr-only">Toggle navigation</span>
      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 7a1 1 0 100 2h12a1 1 0 100-2H4zm0 5a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd"></path></svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbarSupportedContent">
      <ul class="flex flex-col mt-4 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium">
        @guest
         

          @if (Route::has('register'))
            <li>
              <a href="{{ route('register') }}" class="block py-2 pr-4 pl-3 text-gray-700 rounded md:bg-transparent text-white md:p-0 dark:text-white">{{ __('Register') }}</a>
            </li>
          @endif
        @else
        <li class="relative">
        <button type="button" class="flex items-center text-gray-700 rounded md:bg-transparent text-white md:p-0 dark:text-white" data-dropdown-toggle="dropdownExample">
  <span class="mr-1">Jean</span>
  <i class="fas fa-angle-down"></i>
</button>

  <div id="dropdownExample" class="hidden absolute right-0 mt-2 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700" data-popper-placement="bottom-start">
    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLargeButton">
      <li>
        <a href="https://claigames.store/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout <i class="fas fa-sign-out-alt"></i></a>
      </li>
    </ul>
  </div>
  <form id="logout-form" action="https://claigames.store/logout" method="POST" class="hidden">
    @csrf
  </form>
</li>


        @endguest
      </ul>
    </div>
  </div>
</nav>


        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body> 
</html>
