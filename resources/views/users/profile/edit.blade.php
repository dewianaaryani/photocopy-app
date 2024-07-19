@extends('layouts.app')

@section('title')
Edit Profile ({{ $user->name }})
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Edit Profile</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-md-8">
        @if(session('message'))
          <div class="alert alert-primary">
            {{ session('message') }}
          </div>
        @endif

        <div class="card">
          <div class="card-header">
            <h4>Update Profile</h4>
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('updateUser', ['user' => $user->id]) }}">
              @csrf
              @method('PATCH')

              <div class="form-group row mb-4">
                <label for="name" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name</label>
                <div class="col-md-7">
                  <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                  @error('name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="form-group row mb-4">
                <label for="email" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                <div class="col-md-7">
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                  @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              @if(auth()->user()->can('edit-users') && !$user->isMe())
              <div class="form-group row mb-4">
                <label for="role" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                <div class="col-md-7">
                  <select id="role" class="form-control @error('role') is-invalid @enderror" name="role">
                    @foreach($roles as $role)
                      <option value="{{ $role->id }}" {{ (old('role', $user->roles->first()->id) == $role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                  </select>
                  @error('role')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              @endif

              <div class="form-group row mb-4">
                <label for="password" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">New Password</label>
                <div class="col-md-7">
                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                  @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="form-group row mb-4">
                <label for="password_confirmation" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Confirm Password</label>
                <div class="col-md-7">
                  <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                </div>
              </div>

              <div class="form-group row mb-4">
                <div class="col-md-7 offset-md-3">
                  <button type="submit" class="btn btn-primary">
                    Update
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
