<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laboratório de Hardware do CIn</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    
    @livewireStyles
    <style>
        body {
            font-family: Nunito,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 flex">
  <aside class="relative bg-red-700 h-screen w-64 hidden sm:block">
    <div class="p-6">
      <a href="{{route('baias')}}" class="text-white text-3xl font-semibold uppercase">LabCin</a>
    </div>
    <nav class="text-white text-base font-semibold pt-3">
      <a href="{{route('baias')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
        <i class="fas fa-grip-vertical mr-3"></i>
        Baias
      </a>
      <a href="{{route('equipamentos')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
        <i class="fas fa-desktop mr-3"></i>
        Equipamentos
      </a>

      <a href="{{route('reservas')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
        <i class="fas fa-tasks mr-3"></i>
        Reservas
      </a>
      
      @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
        <a href="{{route('monitores')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
          <i class="fas fa-users mr-3"></i>
          Monitores
        </a>
        
        <a href="{{route('log')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
          <i class="fas fa-book mr-3"></i>
          Change Log
        </a>
      @endif
      
      <a href="{{route('logout')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
        <i class="fas fa-sign-out-alt mr-3"></i>
        Sair
      </a>
    </nav>
  </aside>
  <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
    <!-- Desktop Header -->
    <header x-data="{ isOpen: false }" class="w-full bg-red-700 py-5 px-6 sm:hidden">
      <div class="flex items-center justify-between">
          <a class="text-white text-3xl font-semibold uppercase hover:text-gray-300">LabCin</a>
          <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">
              <i x-show="!isOpen" class="fas fa-bars"></i>
              <i x-show="isOpen" class="fas fa-times"></i>
          </button>
      </div>

      <!-- Dropdown Nav -->
      <nav :class="isOpen ? 'flex': 'hidden'" class="flex flex-col pt-4">
        <a href="{{route('baias')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
          <i class="fas fa-grip-vertical mr-3"></i>
          Baias
        </a>
        <a href="{{route('equipamentos')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
          <i class="far fa-address-card mr-3"></i>
          Equipamentos
        </a>
        <a href="{{route('reservas')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
          <i class="fas fa-tasks mr-3"></i>
          Reservas
        </a>

        @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
          <a href="{{route('monitores')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
            <i class="fas fa-users mr-3"></i>
            Monitores
          </a>
          <a href="{{route('log')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
            <i class="fas fa-book mr-3"></i>
            Change Log
          </a>
          @endif
        <a href="{{route('logout')}}" class="flex items-center text-white opacity-75 hover:opacity-100 py-3 pl-6 m-2 rounded-lg hover:shadow-lg hover:bg-white hover:text-red-700 hover:font-bold">
          <i class="fas fa-sign-out-alt mr-3"></i>
          Sair
        </a>
      </nav>
    </header>
    <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
      <main class="w-full flex-grow p-6">
        @yield('body')
      </main>
  </div>
  </div>
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  @livewireScripts
  @stack('scripts')
  @include('sweetalert::alert')
</body>
</html>