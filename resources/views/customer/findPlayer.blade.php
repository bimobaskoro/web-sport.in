<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/findPlayer.css">
  <title>Bootstrap Site</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-v0MYYaTtsXWRRb/yjLAIvFBxSPL2rtDEpufFZ9eF5edWhJR5hlz9NvT06tY2bXWW" crossorigin="anonymous">
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
      <div class="container mt-5">
        <div class="row">
          <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filter</h5>
                    <form action="{{ route('findPlayer') }}" method="GET" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="post_type" class="form-label">Filter Type:</label>
                            <select name="post_type" id="post_type" class="form-select">
                                <option value="all" {{ request('post_type') == 'all' ? 'selected' : '' }}>All Posts</option>
                                <option value="latest" {{ request('post_type') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ request('post_type') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="yourPosts" {{ request('post_type') == 'yourPosts' ? 'selected' : '' }}>Your Posts</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                    </form>
                </div>
            </div>
        </div>
        
            <div class="col-8">
           <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
              Upload Post
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form id="postingForm" action="{{ route('create.post') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="hidden" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}">
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                      </div>
                      <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title">
                      </div>
                      <div class="mb-3">
                        <label for="maxPlayer" class="form-label">Enter the desired number of players</label>
                        <input type="number" class="form-control" id="maxPlayer" name="maxPlayer">
                      </div>
                      <div class="mb-3">
                        <label for="desc" class="form-label">Description</label>
                        <input type="text" class="form-control" id="desc" name="desc">
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

            @foreach ($filter as $posting)
            <div class="card mb-3 mt-3">
                <div class="card-body">
                    @if (auth()->check() && $posting->user_id === auth()->user()->id)
                        <div class="d-flex justify-content-end">
                          <button type="button" class="btn btn-primary mr-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $posting->id }}">
                            Edit Post
                          </button>
                          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $posting->id }}">
                            Delete Post
                        </button>
                        </div>
                    @endif
                    <h5 class="card-title ml-3">{{ $posting->title }}</h5>
                    <p class="card-text ml-3">Need Players: {{ $posting->maxPlayer }}</p>
                    <p class="card-text ml-3">Description: {{ $posting->desc }}</p>
                    <div class="container mt-3">
                      @auth
                          @if(auth()->user()->is_verified)
                              <a href="{{ route('redirectWA', ['postId' => $posting->id]) }}" class="btn btn-success" data-bs-target="{{ route('redirectWA', ['postId' => $posting->id]) }}">
                          @else
                              <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verificationModal">
                          @endif
                      @else
                          <a href="{{ route('redirectWA', ['postId' => $posting->id]) }}" class="btn btn-success" data-bs-target="{{ route('redirectWA', ['postId' => $posting->id]) }}">
                      @endauth
                          <img src="img/wa.png" alt="WhatsApp Icon" class="whatsapp-icon d-inline mr-2"> Chat via WhatsApp
                      </a>
                  </div>
                                  </div>
            </div>
        @endforeach
        
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
      @if ($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
@endif
        @foreach ($filter as $posting)
          
                  <div class="modal fade" id="editModal{{ $posting->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $posting->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $posting->id }}">Edit Post</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('update.post', ['postId' => $posting->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ $posting->title }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="maxPlayer" class="form-label">Enter the desired number of players</label>
                                        <input type="number" class="form-control" id="maxPlayer" name="maxPlayer" value="{{ $posting->maxPlayer }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="desc" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="desc" name="desc" value="{{ $posting->desc }}">
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
            </div>
          </div>
          @endforeach
          
          @foreach($filter as $posting)
          <div class="modal fade" id="deleteModal{{ $posting->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $posting->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel{{ $posting->id }}">Delete Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('delete.post', ['postId' => $posting->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>Are you sure you want to delete this post?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
          @endforeach
            </div>
            </div>
        </div>
      </div>

      
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
      integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>
</html>