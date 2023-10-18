@extends('layouts.simple.master')
@section('title', 'Edit Department Table')

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
                                <h5>Edit Department</h5>
                            </div>
                            <div class="card-body">
                                <form class="form theme-form" action="{{route('department.update', ['department' => $model->id])}}" method="POST" enctype="multipart/form-data">
                                    @method('put')
                                    @csrf
                                    <div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Name</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='name' value='{{$model->name}}' placeholder='Name'>
                                            @error('name')
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
                                        <div class='col-3'></div>
                                        <div class='col-sm-7'>
                                                <input class='form-control' name='image' type='file'>
                                                @error('image')
                                                <p class='text-danger'>{{$message}}</p>
                                                @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Description</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='description' value='{{$model->description}}' placeholder='Description'>
                                            @error('description')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Phone</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='phone' value='{{$model->phone}}' placeholder='Phone'>
                                            @error('phone')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="col-sm-9 offset-sm-3">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                            <a class="btn btn-secondary" href="{{route('department.index')}}">Cancel</a>
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
