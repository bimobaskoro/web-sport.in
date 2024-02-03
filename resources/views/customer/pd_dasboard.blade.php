<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="/css/jquery.rateyo.css">
  <link rel="stylesheet" href="/css/pd_dasboard_style.css">
  <link rel="stylesheet" href="/css/datepicker.css">
  
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
    <div class="row">
            <div class="col-md-6">
                <h4>Type: {{ $product->type }}</h4>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <h4>{{ $product->location }}</h4>
            </div>
        </div>
        <div class="card" style="width: auto;">
                 @if($product->imgUrl)
                    <img src="{{ Storage::url('public/images-post/' . $product->imgUrl) }}" style="max-height: 400px;" class="card-img-top" alt="Product Image">
                @else
                    <img src="placeholder-image.jpg" class="card-img-top" alt="No Image">
                @endif            
            <div class="card-body">
                <h5 class="card-title">{{ $product->nameField }}</h5>
                <p class="card-text">Rp.{{ $product->price }}</p>
                <p class="card-desc clock">{{ $product->openClock }} <strong>s/d</strong> {{ $product->closeClock}}</p>
                <p class="card-desc">{{ $product->desc}}</p>
                <button class="btn btn-primary" data-bs-toggle="modal" 
                    @auth
                        @if(auth()->user()->is_verified)
                            data-bs-target="#exampleModal"
                        @else
                            data-bs-target="#verificationModal"
                        @endif
                    @endauth
                >
                    Booking
                </button>
                <a href="{{ route('redirecttoWA', ['productId' => $product->id]) }}" class="btn btn-success" 
                    @auth
                        @if(auth()->user()->is_verified)
                            data-bs-target="{{ route('redirecttoWA', ['productId' => $product->id]) }}"
                        @else
                            data-bs-toggle="modal" data-bs-target="#verificationModal"
                        @endif
                    @else
                        data-bs-target="{{ route('redirecttoWA', ['productId' => $product->id]) }}"
                    @endauth
                >
                    Chat
                </a>
                
                 <input type="hidden" name="rating" id="rating_input">   
            </div>
        <div class="container review">
        <form method="POST" action="{{ route('reviewProduk', ['id' => $product->id]) }}">
                @csrf
                <div class="card-review">
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="review-title">Review</div>
            
                        <div class="review-star">
                            <div class="star-rating">
                                <span class="star" data-value="1">&#9733;</span>
                                <span class="star" data-value="2">&#9733;</span>
                                <span class="star" data-value="3">&#9733;</span>
                                <span class="star" data-value="4">&#9733;</span>
                                <span class="star" data-value="5">&#9733;</span>
                             </div>
                            <select class="form-select mb-3"  name="rating" id="rating" aria-label="Default select example">
                                <option selected>Select The Star</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                                <option value="4">Four</option>
                                <option value="5">Five</option>
                            </select>   
                         </div>

                <div class="container">
                    <div>
                        <textarea class="form-control" placeholder="Leave a comment here" id="comment" name="comment" style="height: 100px; width: 100%;"></textarea>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">Submit</button>
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
             @elseif (Session::has('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ Session::get('error') }}',
                });
            </script>
             @endif

        
            </div>

            @if(isset($reviews) && count($reviews) > 0)
                @foreach ($reviews as $review)
                    <div class="card mt-3 mb-3" style="width: auto;">
                    <div class="card-body">
                        <h3>{{ $review->user->name }}</h3>
                        <p>Rating:
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <span class="text-warning">&#9733;</span>
                                @else
                                    <span class="text-secondary">&#9733;</span>
                                @endif
                            @endfor
                        </p>                       
                         <p>{{ $review->comment }}</p>
                    </div>
                    </div>
                @endforeach
            @else
                <p>Tidak ada ulasan untuk produk ini.</p>
            @endif

        </div>  
        
         <!-- Modal Booking-->
         <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Booking {{ $product->nameField }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="mb-3">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">  
                                <label for="name" class="form-label">Your Name</label>
                                <input type="hidden" class="form-control" id="name" name="name" value="{{auth()->user()->name}}">
                                <input type="text" class="form-control" value="{{auth()->user()->name}}" disabled>
                                <input type="hidden" class="form-control" id="name" name="user_id" value="{{auth()->user()->id}}">
                            </div>
                                    <div class="mb-3">
                                        <label for="field_name" class="form-label">Name Field</label>
                                        <input type="text" class="form-control" value="{{ $product->nameField }}" disabled>
                                        <input type="hidden" class="form-control" id="field_name" name="field_name" value="{{ $product->nameField }}">
                                    </div>
                             <div class="mb-3">
                                 <label for="location" class="form-label">Location</label>
                                     <input type="text" class="form-control" value="{{ $product->location }}" disabled>
                                     <input type="hidden" class="form-control" id="location" name="location" value="{{ $product->location }}">
                            </div>
                           
                            <div class="mb-3">
                                <label for="location" class="form-label">Type</label>
                                    <input type="text" class="form-control" value="{{ $product->type }}" disabled>
                                    <input type="hidden" class="form-control custom-width" id="type" name="type" value="{{ $product->type }}">
                           </div>
                           <div class="mb-3">
                                <div class="row">
                                    <div class="col-sm-3 d-flex align-items-center justify-content-center">
                                        <label for="date" class="form-label">Select Date</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="booking_date" id="booking_date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row text-center">
                                    <label for="startHour" class="form-label">Select Clock:</label>
                                </div>
                                <div class="row">
                                    <div class="col-5 text-end">
                                        <select name="start_booking_hour" id="startHour" class="form-control" required></select>
                                    </div>
                                    <div class="col-2 text-center">
                                        <span class="form-label">-</span>
                                    </div>
                                    <div class="col-5 text-start">
                                        <select name="finish_booking_hour" id="endHour" class="form-control" required></select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="selectedField">Select Field:</label>
                                @if ($product->totalField > 0)
                                    <div class="row">
                                        @for ($i = 1; $i <= $product->totalField; $i++)
                                            <div class="col-3 mb-2">
                                                <input type="radio" name="selectedField" id="field{{ $i }}" value="{{ $i }}" onclick="checkFieldAvailability()">
                                                <label for="field{{ $i }}">Field {{ $i }}</label>
                                            </div>
                                        @endfor
                                    </div>
                                @else
                                    <p>No fields available for booking.</p>
                                @endif
                            
                                <p id="availabilityMessage"></p>
                            </div>
                                                                               
                            <div class="mb-3">
                                <h5>Price : {{ $product->price}}/Hours </h5>
                                <input type="hidden" class="form-control" name="price" value="{{$product->price}}">
                            </div>
                            <div class="mb-3">
                                <h5>Price Total: <span id="totalPrice">Rp.0</span></h5>
                                <input type="hidden" name="total_price" id="totalPriceInput">
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Book</button>
                    </div>
                    </div>
                </div>
                </form>
            </div>            
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            @if (Session::has('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ Session::get('success') }}',
                });
            </script>
             @elseif (Session::has('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ Session::get('error') }}',
                });
            </script>
             @endif

        
    </div>
    <!-- Modal Verifikasi -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verificationModalLabel">Verifikasi Akun Anda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda perlu melakukan verifikasi akun sebelum dapat melakukan booking. Klik tombol di bawah untuk mengirim kode verifikasi via WhatsApp.</p>
                    <form action="{{ route('send-verification-code') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary">Kirim Kode Verifikasi via WhatsApp</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

{{-- Tampilkan pesan kesalahan dari validasi form --}}
@if ($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
@endif
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
     </script>
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/datepicker.js"></script>
    
    <script>
     const selectElement = document.querySelector('.form-select');
    const ratingStars = document.querySelectorAll('.star');

    selectElement.addEventListener('change', (event) => {
        const selectedValue = parseInt(event.target.value);

        // Hapus kelas "active" dari semua bintang
        ratingStars.forEach(star => star.classList.remove('active'));

        // Tambahkan kelas "active" ke bintang yang sesuai
        for (let i = 0; i < selectedValue; i++) {
            ratingStars[i].classList.add('active');
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
    var startHourSelect = document.getElementById("startHour");
    var endHourSelect = document.getElementById("endHour");
    var openingHour = {{ $openingHour }}; // Jam buka dari data produk
    var closingHour = {{ $closingHour }}; // Jam tutup dari data produk
    var hourlyRate = {{ $product->price }}; // Harga per jam dari data produk
    
    // Menambahkan jam ke dalam dropdown secara dinamis antara jam buka dan tutup
    for (var i = openingHour; i <= closingHour; i++) {
        var option = document.createElement("option");
        option.text = i.toString().padStart(2, '0') + ":00"; // Format jam 2 digit (00, 01, ..., 23)
        option.value = i;
        startHourSelect.appendChild(option.cloneNode(true));
        endHourSelect.appendChild(option);
    }

    // Menghitung total harga berdasarkan jam mulai dan jam selesai yang dipilih
    function calculateTotalPrice() {
        var startHour = parseInt(startHourSelect.value);
        var endHour = parseInt(endHourSelect.value);

            if (endHour <= startHour) {
            alert("Waktu selesai harus setelah waktu mulai. Silakan pilih kembali.");
            endHourSelect.value = startHour + 1;
            endHour = startHour + 1;  // Update nilai endHour
        }

        var numberOfHours = endHour - startHour;
        var totalPrice = numberOfHours * hourlyRate;

        // Tampilkan total harga atau lakukan apa pun yang Anda inginkan dengan nilai totalPrice
        console.log("Total Harga: Rp." + totalPrice);
        document.getElementById("totalPrice").textContent = "Rp." + totalPrice;
        document.getElementById("totalPriceInput").value = totalPrice;
    }

    // Mendengarkan perubahan pada dropdown jam mulai dan selesai
    startHourSelect.addEventListener("change", calculateTotalPrice);
    endHourSelect.addEventListener("change", calculateTotalPrice);

});


    
    </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>