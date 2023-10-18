@extends('layouts.simple.master')
@section('title', 'Add RentBook Table')

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
                                <h5>Add RentBook</h5>
                            </div>
                            <div class="card-body">
                                <form class="form theme-form" action="{{route('rent_book.store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>User Id</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='user_id' value="{{old('user_id')}}" placeholder='User Id'>
                                            @error('user_id')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Book Id</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='book_id' value="{{old('book_id')}}" placeholder='Book Id'>
                                            @error('book_id')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Star Time</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='star_time' value="{{old('star_time')}}" placeholder='Star Time'>
                                            @error('star_time')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>End Time</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='end_time' value="{{old('end_time')}}" placeholder='End Time'>
                                            @error('end_time')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Is Taken</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='is_taken' value="{{old('is_taken')}}" placeholder='Is Taken'>
                                            @error('is_taken')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="col-sm-9 offset-sm-3">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                            <a class="btn btn-secondary" href="{{route('rent_book.index')}}">Cancel</a>
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
