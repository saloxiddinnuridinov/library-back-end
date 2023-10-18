@extends('layouts.simple.master')
@section('title', 'Add User Table')

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h5>Delete User</h5>
                            </div>
                            <div class="card-body">
                                <form class="form theme-form" action="{{route('user.destroy',['user' => $id])}}" method="POST" enctype="multipart/form-data">
                                    @method('delete')
                                    @csrf
                                    <div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Name</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control' disabled  type='text' name='name' value='{{$model->name}}' placeholder='Name'>
                                            @error('name')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Surname</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control' disabled  type='text' name='surname' value='{{$model->surname}}' placeholder='Surname'>
                                            @error('surname')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Email</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control' disabled  type='text' name='email' value='{{$model->email}}' placeholder='Email'>
                                            @error('email')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Password</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control' disabled  type='text' name='password' value='{{$model->password}}' placeholder='Password'>
                                            @error('password')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Image</label>
                                        <div class='col-2'>
                                            <img src='{{$model->image}}' width='100' alt=''>
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Qr Code</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control' disabled  type='text' name='qr_code' value='{{$model->qr_code}}' placeholder='Qr Code'>
                                            @error('qr_code')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Group</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control' disabled  type='text' name='group' value='{{$model->group}}' placeholder='Group'>
                                            @error('group')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Role</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control' disabled  type='text' name='role' value='{{$model->role}}' placeholder='Role'>
                                            @error('role')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="col-sm-9 offset-sm-3">
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                            <a class="btn btn-primary" href="{{route('user.index')}}">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
