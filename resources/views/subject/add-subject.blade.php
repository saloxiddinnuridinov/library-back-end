@extends('layouts.simple.master')
@section('title', 'Add Subject Table')

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
                                <h5>Add Subject</h5>
                            </div>
                            <div class="card-body">
                                <form class="form theme-form" action="{{route('subject.store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Department Id</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='department_id' value="{{old('department_id')}}" placeholder='Department Id'>
                                            @error('department_id')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Name</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='name' value="{{old('name')}}" placeholder='Name'>
                                            @error('name')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Tag</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='tag' value="{{old('tag')}}" placeholder='Tag'>
                                            @error('tag')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Position</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='position' value="{{old('position')}}" placeholder='Position'>
                                            @error('position')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
									<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>Description</label>
                                        <div class='col-sm-9'>
                                            <textarea id='message' name='description' rows='1' cols='111' placeholder='Description'>{{value(old('description'))}}</textarea>
                                            @error('description')
                                            <p class='text-danger'>{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="col-sm-9 offset-sm-3">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                            <a class="btn btn-secondary" href="{{route('subject.index')}}">Cancel</a>
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
