<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
  <script type="text/javascript"
		src="https://app.stg.midtrans.com/snap/snap.js"
    data-client-key="SB-Mid-client-XnTH_qZ1Jk5CvY6K"></script>
  <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
  <title>Bootstrap Site</title>
  <link rel="stylesheet" href="/css/checkOut_style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
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
        <div class="card">
            <form action="{{ isset($newBooking) ? route('checkout') : route('checkoutHistory', ['booking_id' => $booking->id]) }}" method="POST">
                @csrf
            <h2 class="mt-3 ml-3">Detail Order</h2>
            <table class="ml-3">
                <tr>
                    <td>Name</td>
                    <td> : {{ isset($newBooking) ? $newBooking->name : $booking->name }}</td>
                </tr>
                <tr>
                    <td>Field Name</td>
                    <td> : {{ isset($newBooking) ? $newBooking->field_name : $booking->field_name }}</td>
                </tr>
                <tr>
                    <td>Type Sport</td>
                    <td> : {{ isset($newBooking) ? $newBooking->type : $booking->type }}</td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td> : {{ isset($newBooking) ? $newBooking->location : $booking->location }}</td>
                </tr>
                <tr>
                    <td>Start Clock</td>
                    <td> : {{ isset($newBooking) ? $newBooking->start_booking_hour : $booking->start_booking_hour }}</td>
                </tr>
                <tr>
                    <td>Finish Clock</td>
                    <td> : {{ isset($newBooking) ? $newBooking->finish_booking_hour : $booking->finish_booking_hour }}</td>
                </tr>
                <tr>
                    <td>Total Price</td>
                    <td> : {{ isset($newBooking) ? $newBooking->total_price: $booking->total_price }}</td>
                </tr>
                <tr>
                    <td><button type="button" id="pay-button" class="btn btn-primary mt-3 mb-3">Pay Now</button>
                    </td>
                </tr>
        </table>

        </div>
    </div>
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // SnapToken acquired from previous step
            snap.pay('{{ $snapToken }}', {
                // Optional
                onSuccess: function(result){
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                },
                // Optional
                onPending: function(result){
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                },
                // Optional
                onError: function(result){
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                }
            });
        };
    </script>
</body>
</html>