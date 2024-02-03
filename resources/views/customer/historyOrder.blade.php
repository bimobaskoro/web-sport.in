<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

  <link rel="stylesheet" href="/css/historyOrder.css">
  <title>Bootstrap Site</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
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

  <div class="title-order mt-3 text-center">
    <h1>Your Orders</h1>
  </div>
  
<!-- ... (your existing code) ... -->

<div class="container mt-5">
  <div class="row">
    <div class="col-4">
      <div class="card">
          <div class="card-body">
              <h5 class="card-title">Filters</h5>
              <form action="{{ route('historyOrder.filter') }}" method="GET">
                <div class="mb-3">
                  <label for="status" class="form-label">Status:</label>
                  <select name="status" class="form-control" id="status">
                    <option value="">All</option>
                    <option value="paid" @if(session('filter.status') == 'paid') selected @endif>Paid</option>
                    <option value="pending" @if(session('filter.status') == 'pending') selected @endif>Pending</option>
                    <option value="cancel" @if(session('filter.status') == 'cancel') selected @endif>Cancel</option>
                </select>
                
              </div>
      
                        <!-- Tambahkan field untuk memilih urutan harga -->
              <div class="mb-3">
                <label class="form-label">Price Order:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="price_order" id="low_price" value="asc" checked>
                    <label class="form-check-label" for="low_price">
                        Lowest Price
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="price_order" id="high_price" value="desc">
                    <label class="form-check-label" for="high_price">
                        Highest Price
                    </label>
                </div>
              </div>

                <div class="mb-3">
                    <label for="sort_order" class="form-label">Sort Order:</label>
                    <select name="sort_order" class="form-control">
                        <option value="desc">Newest First</option>
                        <option value="asc">Oldest First</option>
                    </select>
                </div>
            
                <button type="submit" name="apply_filters" class="btn btn-primary">Apply Filters</button>
            </form>
            
          </div>
      </div>
  </div>
      <div class="col-8">
        @foreach($filteredBookings as $booking)
               <div class="card mb-3">
                  <div class="card-body">
                      <div class="row">
                          <div class="col-6">
                              <h5 class="card-title">{{ $booking->field_name }} | {{ $booking->type }}</h5>
                          </div>
                          <div class="col-6" style="text-align: right;">
                              <h5 class="card-title">{{ $booking->location }}</h5>
                          </div>
                      </div>
                      <div class="horizontal-line mt-2"></div>
                      <table class="ml-3 mt-3">
                        <tr>
                          <td class="card-text">Name</td>
                          <td> : {{$booking->name}}</td>
                        </tr>
                        <tr>
                          <td class="card-text">Date</td>
                          <td> : {{$booking->booking_date}}</td>
                        </tr>
                        <tr>
                          <td class="card-text">Clock</td>
                          <td> : {{ date('H:i', strtotime($booking->start_booking_hour.':00')) }} - {{ date('H:i', strtotime($booking->finish_booking_hour.':00')) }}</td>
                        </tr>
                        <tr>
                          <td class="card-text">Price</td>
                          <td> : Rp.{{$booking->price}}</td>
                        </tr>                      </table>
                      <div class="horizontal-line mt-2"></div>
                      <div class="row mt-2">
                          <div class="col-6">
                              <div class="button ml-3 mb-3">
                                @if($booking->status == 'paid')
                                <button type="button" class="btn btn-primary">Paid</button>
                                @elseif($booking->status == 'cancel')
                                    <button type="button" class="btn btn-danger">Cancel</button>
                                @elseif($booking->status == 'pending')
                                    <button type="button" class="btn btn-success">Pending</button>
                                    <a href="{{ route('checkoutHistory', ['booking_id' => $booking->id]) }}" class="btn btn-success">Pay</a>
                                @endif
                          </div>
                          </div>
                          <div class="col-6" style="text-align: right;">
                              <h5 class="card-title mr-3">Price Total : Rp.{{$booking->total_price}}</h5>
                          </div>
                      </div>
                  </div>
              </div>
          @endforeach
      </div>
  </div>
</div>

<!-- ... (your existing code) ... -->
<!-- ... (your existing code) ... -->
<!-- ... (your existing code) ... -->

<script>
 document.addEventListener('DOMContentLoaded', function () {
    // Reset nilai filter jika tombol "Apply Filters" tidak ditekan
    if (!document.getElementById('apply_filters').clicked) {
        document.getElementById('status').value = "";
        document.getElementById('low_price').checked = true;
        document.getElementById('high_price').checked = false;
        document.getElementById('sort_order').value = "desc";
    }
});

</body>
</html>