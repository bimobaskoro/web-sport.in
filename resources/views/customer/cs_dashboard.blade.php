<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Website Olahraga</title>
  <link rel="stylesheet" href="/css/cs_dashboard_style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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

      <div class="container mt-5">
        <div class="row text-center">
            <a href="{{route('findPlayer')}}">Have you found a playmate or opponent?</a>
        </div>
      </div>
      <div class="container mt-5 icon">
        <div class="row justify-content-center">
            <div class="col-2">
                  <a href="{{ route('dashboardSoccer') }}" class="custom-link">
                    <span class="material-icons" style="font-size: 2em;">sports_soccer</span>
                  </a>
              </div>
              <div class="col-2">
                <a href="{{ route('dashboardIndoorFootball') }}" class="custom-link">
                  <span class="material-icons" style="font-size: 2em;">sports_soccer</span>
              </a>
              </div>
            <div class="col-2">
            <a href="{{ route('dashboardBadminton') }}" class="custom-link" >
                  <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 32 32" id="shuttlecock"><path d="M19.83 22h-7.66A1.17 1.17 0 0 0 11 23.17V25a5 5 0 0 0 10 0v-1.83A1.17 1.17 0 0 0 19.83 22zM15.41 5.37V21H12.9L11.29 5.37A2.23 2.23 0 0 1 13.35 3a2.23 2.23 0 0 1 2.06 2.37zM11.68 21h-1.29L6 6.32v-.11a2.12 2.12 0 0 1 2-2 2.14 2.14 0 0 1 2.06 2.22zM26 6.32 21.61 21h-1.29l1.56-14.59v-.06A2 2 0 1 1 26 6.21zm-5.29-1.03L19.1 21h-2.51V5.37A2.23 2.23 0 0 1 18.65 3a2.2 2.2 0 0 1 2.06 2.29z" data-name="Layer 2"></path></svg>       
            </a>
            </div>
            <div class="col-2">
              <a href="{{ route('dashboardBasketBall') }}" class="custom-link" >
                <span class="material-icons" style="font-size: 2em;">sports_basketball</span>
              </a>
            </div>
            <div class="col-2">
              <a href="{{ route('dashboardVolley') }}" class="custom-link" >
                <span class="material-icons" style="font-size: 2em;">sports_volleyball</span>
              </a>
            </div>

        </div>
      </div>

      <div class="container text-icon">
        <div class="row justify-content-center">
            <div class="col-2">
                 <p>Soccer</p>
              </div>
              <div class="col-2">
              <p>Indoor Football</p>
              </div>
            <div class="col-2">
              <p>Badminton</p>
            </div>
            <div class="col-2">
              <p>Basketball</p>
            </div>
            <div class="col-2">
              <p>Volleyball</p>
            </div>
        </div>
      </div>

      <div class="container mt-3 text-icon">
    <div class="row">
        @foreach($products as $product)
        <div class="col-2 product-field" data-type="{{ $product->type }}">
            <div class="card" style="width: 15rem;">
                @if($product->imgUrl)
                <a href="{{ route('productDetail', ['id' => $product->id]) }}">
                    <img src="{{ Storage::url('public/images-post/' . $product->imgUrl) }}" style="max-height: 130px;" class="card-img-top" alt="Product Image">
                </a>
                @else
                <img src="placeholder-image.jpg" class="card-img-top" alt="No Image">
                @endif
                <div class="card-body">
                    <h5 class="card-title cardTitle">{{ $product->nameField }}</h5>
                    <p class="card-text">Rp.{{ $product->price }}</p>
                    <p class="card-text">{{ $product->openClock }} <strong>s/d</strong> {{ $product->closeClock}}</p>
                    <div class="row">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
                            <!-- Your SVG code -->
                   
                        <p class="card-text">{{ $product->location }}</p>
                    </div> 
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


   
<!-- Bootstrap JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
