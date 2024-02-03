<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Website Olahraga</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <link rel="stylesheet" href="css/profile.css">
  <link rel="stylsheet" href="{fa{'fontawesome-free-6.4.2-web'}}">
</head>
<body>
  <nav class="navbar navbar-light shadow">
    <div class="container-fluid">
      <a href="{{ route('dashboard') }}" class="navbar-brand">Sport.in</a>
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
        <a href="{{url('/historyOrder')}}">History Order</a>
        <a href="{{ route('logout') }}">Logout</a>
      </div>
      </div>
    </div>
  </nav>

      <div class="row justify-content-center mt-5">
        <div class="col-lg-11">
            <div class="card">
              <div class="card-header">
                <h1 class="card-title">Profile</h1>
              </div>
              <div class="card-body">
              <form method="POST" action="{{ route('csProfileUpdate', ['id' => $user->id])}}">
                @csrf
                 <div class="mb-3">
                   <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" value="{{ auth()->user()->name }}">
                 </div>     
                  <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" value="{{ auth()->user()->email }}">                              
                  </div>
                  <div class="mb-3">
                      <label for="phone" class="form-label">Phone Number</label>
                      <input type="phone" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone }}"> 
                  </div>
                  <div class="mb-3">
                      <div class="d-grid">
                          <button class="btn btn-primary">Update</button>
                      </div>
                   </div>
                </form>       
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                @if (Session::has('success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: '{{ Session::get('success') }}',
                                });
                            </script>
                 @endif        
              </div>
            </div>
        </div>
      </div>
<!-- Bootstrap JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>

</body>
</html>
