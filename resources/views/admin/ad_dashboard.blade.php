<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <title>Website Olahraga</title> -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/ad_dashboard_style.css">
  <link rel="stylsheet" href="{fa{'fontawesome-free-6.4.2-web'}}">

</head>

<body>
<nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand">Website Olahraga Admin</a>
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
                <a href="{{ route('logout') }}">Logout</a>
              </div>
          </div>
        </div>
  </nav>

  <!-- Button trigger modal -->
<button type="button" class="btn btn-primary mt-3 ml-3 mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Add Field
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('ad_dashboardPost') }}" enctype="multipart/form-data">
          @csrf
          <img class="imgField" id="profile-pic" >
          <label class="label-update text-center mt-3" for="input-file">Update Image</label>
          <input type="file" accept="image/jpeg, image/png, image/jpg" id="input-file" name="imgUrl">
          <div class="mb-3 mt-3">
            <label for="nameField" class="form-label">Field Name</label>
            <input type="text" name="nameField" class="form-control" id="nameField" placeholder="Name Field">                         
         </div>
          <div class="mb-3">
          <label for="location" class="form-label">Location</label>
          <select name="location" id="loaction" class="form-select" aria-label="Default select example">
            <option selected>Select location</option>
            <option value="Jakarta Timur">Jakarta Timur</option>
            <option value="Jakarta Barat">Jakarta Barat</option>
            <option value="Jakarta Selatan">Jakarta Selatan</option>
            <option value="Jakarta Utara">Jakarta Utara</option>
            <option value="Jakarta Pusat">Jakarta Pusat</option>
          </select>
        </div>
         <div class="mb-3 mt-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" name="price" class="form-control" id="price" placeholder="Price">                         
         </div>
        <div class="mb-3">
          <div class="row">
            <div class="col">
              <label for="openClock" class="form-label">Open Clock</label>
              <select name="openClock" id="startHourSelect"></select>
            </div>
            <div class="col">
              <label for="closeClock" class="form-label">Close Clock</label>
              <select name="closeClock" id="endHourSelect"></select>
            </div>
          </div>
      </div>
      <div class="mb-3">
        <label for="price" class="form-label">Total Field</label>
        <input type="text" name="totalField" class="form-control" id="price" placeholder="Total Field">                         
      </div>
        <div class="mb-3">
          <label for="type" class="form-label">Sport Type</label>
          <select name="type" id="type" class="form-select" aria-label="Default select example">
            <option selected>Select Sport Type</option>
            <option value="Soccer">Soccer</option>
            <option value="Indoor Football">Indoor Football</option>
            <option value="Badminton">Badminton</option>
            <option value="BasketBall">BasketBall</option>
            <option value="Volley">Volley</option>
          </select>
        </div>
        <div class="mb-3">
          <div class="form-floating">
            <label for="desc">Description</label>
            <textarea class="form-control" placeholder="Leave a comment here" id="desc" name="desc" style="height: 100px"></textarea>
          </div>
        </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
    </div>
  </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Field Name</th>
            <th>Location</th>
            <th>Sport Type</th>
            <th>Price</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
      @foreach($products as $product)
          <tr>
              <td>{{ $product->nameField }}</td>
              <td>{{ $product->location }}</td>
              <td>{{ $product->type }}</td>
              <td>{{ $product->price }}</td>
              <td>{{ $product->desc }}</td>
              <td>
                  @if($product->imgUrl)
                      <img src="{{ Storage::url('public/images-post/' . $product->imgUrl) }}" alt="Product Image" width="100">
                  @else
                      No Image
                  @endif
              </td>
              <td>
                  <div class="row">
                      <div class="col">
                          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}">Edit</button>
                      </div>
                      <div class="col">
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}">Delete</button>
                      </div>
                  </div>
              </td>
          </tr>
      @endforeach
  </tbody>  
</table>

@foreach($products as $product)
<div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $product->id }}" aria-hidden="true">
  <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $product->id }}">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulir Edit dengan isian awal dari data produk -->
                    <form method="POST" action="{{ route('ad_dashboardUpdate', ['id' => $product->id]) }}" enctype="multipart/form-data">
                        @csrf
                        <img class="imgField" id="profile-pic-{{ $product->id }}" src="{{ Storage::url('public/images-post/' . $product->imgUrl) }}" alt="Product Image" width="100">
                        <label class="label-update text-center mt-3" for="input-file-{{ $product->id }}">Update Image</label>
                        <input type="file" accept="image/jpeg, image/png, image/jpg" id="input-file-{{ $product->id }}" name="imgUrl">
                        <!-- ... -->
                        <div class="mb-3 mt-3">
                            <label for="nameField" class="form-label">Field Name</label>
                            <input type="text" name="nameField" class="form-control" id="nameField-{{ $product->id }}" placeholder="Name Field" value="{{ $product->nameField }}">
                        </div>
                        <div class="mb-3">
                          <label for="location" class="form-label">Location</label>
                          <select name="location" id="location" class="form-select" aria-label="Default select example">
                              <option value="Jakarta Timur" @if($product->location == 'Jakarta Timur') selected @endif>Jakarta Timur</option>
                              <option value="Jakarta Barat" @if($product->location == 'Jakarta Barat') selected @endif>Jakarta Barat</option>
                              <option value="Jakarta Selatan" @if($product->location == 'Jakarta Selatan') selected @endif>Jakarta Selatan</option>
                              <option value="Jakarta Utara" @if($product->location == 'Jakarta Utara') selected @endif>Jakarta Utara</option>
                              <option value="Jakarta Pusat" @if($product->location == 'Jakarta Pusat') selected @endif>Jakarta Pusat</option>
                          </select>
                      </div>
                      <div class="mb-3 mt-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" name="price" class="form-control" id="price" placeholder="Price" value="{{ $product->price }}">
                    </div>
                    
                    <div class="row">
                     <!-- Dropdown jam buka -->
                   <!-- Dropdown jam buka -->
                      <div class="col">
                        <label for="openClock" class="form-label">Open Clock</label>
                        <select name="openClock" id="startHourSelect" class="form-select">
                            @for ($i = 0; $i < 24; $i++)
                                @php
                                    $formattedHour = sprintf("%02d:00:00", $i);
                                    $value = $i;
                                @endphp
                                <option value="{{ $value }}" @if ($formattedHour == $product->openClock) selected @endif>
                                    {{ $formattedHour }}
                                </option>
                            @endfor
                        </select>
                      </div>

                      <!-- Dropdown jam tutup -->
                      <div class="col">
                        <label for="closeClock" class="form-label">Close Clock</label>
                        <select name="closeClock" id="endHourSelect" class="form-select">
                            @for ($i = 0; $i < 24; $i++)
                                @php
                                    $formattedHour = sprintf("%02d:00:00", $i);
                                    $value = $i;
                                @endphp
                                <option value="{{ $value }}" @if ($formattedHour == $product->closeClock) selected @endif>
                                    {{ $formattedHour }}
                                </option>
                            @endfor
                        </select>
                      </div>


                  </div>
                  
              
                    <div class="mb-3">
                        <label for="totalField" class="form-label">Total Field</label>
                        <input type="text" name="totalField" class="form-control" id="totalField" placeholder="Total Field" value="{{ $product->totalField }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Sport Type</label>
                        <select name="type" id="type" class="form-select" aria-label="Default select example">
                            <option value="Soccer" @if($product->type == 'Soccer') selected @endif>Soccer</option>
                            <option value="Indoor Football" @if($product->type == 'Indoor Football') selected @endif>Indoor Football</option>
                            <option value="Badminton" @if($product->type == 'Badminton') selected @endif>Badminton</option>
                            <option value="BasketBall" @if($product->type == 'BasketBall') selected @endif>BasketBall</option>
                            <option value="Volley" @if($product->type == 'Volley') selected @endif>Volley</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-floating">
                            <label for="desc">Description</label>
                            <textarea class="form-control" placeholder="Leave a comment here" id="desc" name="desc" style="height: 100px">{{ $product->desc }}</textarea>
                        </div>
                    </div>
                        <!-- Tambahkan isian formulir lainnya sesuai kebutuhan -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
              </form>
            </div>
        </div>
    </div>
@endforeach

<!-- ... -->

@foreach($products as $product)
    <!-- Modal Edit -->
    <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $product->id }}" aria-hidden="true">
        <!-- ... -->
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Delete Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form method="POST" action="{{ route('ad_dashboardDelete', ['id' => $product->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- ... -->

  <script>
    let profilePic = document.getElementById("profile-pic");
    let inputFile = document.getElementById("input-file");

    inputFile.onchange = function(){
      profilePic.src = URL.createObjectURL(inputFile.files[0]);
    }
  </script>
  @foreach($products as $product)
  <script>
      let profilePic{{ $product->id }} = document.getElementById("profile-pic-{{ $product->id }}");
      let inputFile{{ $product->id }} = document.getElementById("input-file-{{ $product->id }}");

      inputFile{{ $product->id }}.onchange = function() {
          profilePic{{ $product->id }}.src = URL.createObjectURL(inputFile{{ $product->id }}.files[0]);
      }
  </script>
@endforeach
  <script>
    
    document.addEventListener("DOMContentLoaded", function() {
      var startHourSelect = document.getElementById("startHourSelect");
    var endHourSelect = document.getElementById("endHourSelect");

    // Menambahkan jam ke dalam dropdown secara dinamis
    for (var i = 0; i < 24; i++) {
        var option = document.createElement("option");
        option.text = i.toString().padStart(2, '0') + ":00" + ":00"; // Format jam 2 digit (00, 01, ..., 23)
        option.value = i;
        startHourSelect.appendChild(option);
        
        var endOption = option.cloneNode(true); // Clone opsi untuk dropdown jam selesai
        endHourSelect.appendChild(endOption);
    }

    startHourSelect.addEventListener("change", showSelectedHours);
    endHourSelect.addEventListener("change", showSelectedHours);

    function showSelectedHours() {
        var startHour = startHourSelect.value;
        var endHour = endHourSelect.value;
        selectedHoursText.textContent = "Jam yang dipilih: " + startHour + ":00 sampai " + endHour + ":00";
    }
});
  
  </script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>