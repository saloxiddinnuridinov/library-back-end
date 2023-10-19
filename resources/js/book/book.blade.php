@extends('views.layouts.simple.master')
@section('title', 'Programmer UZ')


@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Book</h3>
@endsection

@section('breadcrumb-items')
    <div class="py-3">
        <div class="justify-content-between row mx-2">
            <a href="{{route('book.create')}}" class="btn btn-success font-weight-bold"><i class="fa fa-plus"></i></a>
        </div>
    </div>
@endsection

@section('content')
    @if(session()->has('message'))
        <div class="alert {{session('error') ? 'alert-danger' : 'alert-success'}}">
            {{ session('message') }}
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Book</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope='col'>Name</th>
                                <th scope='col'>Status</th>
                                <th scope='col'>Description</th>
                                <th scope='col'>Created At</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($models as $model)
                                <tr>
                                    <td>{{$model->id}}</td>
                                    <td>{{$model->name}}</td>
                                    <td>{{$model->status}}</td>
                                    <td>{{$model->description}}</td>
                                    <td>{{$model->created_at}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{route('book.edit', ['book' => $model->id])}}"
                                               class="btn btn-info"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('book.show', ['book' => $model->id])}}"
                                               class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
