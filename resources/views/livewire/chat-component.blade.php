<!-- <!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="/css/jquery.rateyo.css">
  <link rel="stylesheet" href="/css/chat.css">
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-light shadow">
            <div class="container-fluid">
            <a href="{{ route('dashboard') }}" class="navbar-brand">Navbar</a>
            <div class="dropdown" style="float:right;">
                @auth
                Welcome back {{ auth()->user()->name }}
                @endauth
                <i class="fa-solid fa-circle-user fa-2xl"></i>
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-person-circle droptbtn" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                </svg>            
                <div class="dropdown-content">
                <a href="{{url('/csProfile')}}">Profile</a>
                <a href="#">Log Out</a>
                </div>
            </div>
            </div>
    </nav>

    <div class="container mt-5">
    @livewire('chat-component', ['productId' => $productId])
        <div class="row">
            <div class="col-4">
                <!-- Daftar pengguna yang telah diobroli -->
                <ul>
                    @foreach($chats as $chat)
                    <li>{{ $chat->user->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="col-8">
                <!-- Tampilan pesan obrolan -->
                <div>
                    @foreach($chats as $chat)
                    <div>{{ $chat->message }}</div>
                    @endforeach
                </div>
                <!-- Form untuk mengirim pesan baru -->
                <form wire:submit.prevent="sendMessage">
                    <input type="text" wire:model="message">
                    <button type="submit">Kirim</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Livewire Scripts -->
    @livewireScripts
</body>
  </div>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>