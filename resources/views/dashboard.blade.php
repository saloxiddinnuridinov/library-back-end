@extends('layouts.simple.master')

@section('title', 'Programmer UZ')

@section('style')

@endsection

@section('breadcrumb-title')

    <h3>Dashboard</h3>

@endsection

@section('breadcrumb-items')

@endsection

@section('content')
    <div class='col-3 p-3'>
            <div class='card'>
                <div class='card-body'>
                    <a href="{{route('user.index')}}">
                        <h4>User</h4>
                    </a>
                </div>
            </div>
        </div>
        <div class='col-3 p-3'>
            <div class='card'>
                <div class='card-body'>
                    <a href="{{route('department.index')}}">
                        <h4>Department</h4>
                    </a>
                </div>
            </div>
        </div>
        <div class='col-3 p-3'>
            <div class='card'>
                <div class='card-body'>
                    <a href="{{route('subject.index')}}">
                        <h4>Subject</h4>
                    </a>
                </div>
            </div>
        </div>
        <div class='col-3 p-3'>
            <div class='card'>
                <div class='card-body'>
                    <a href="{{route('book.index')}}">
                        <h4>Book</h4>
                    </a>
                </div>
            </div>
        </div>
        <div class='col-3 p-3'>
            <div class='card'>
                <div class='card-body'>
                    <a href="{{route('rent_book.index')}}">
                        <h4>RentBook</h4>
                    </a>
                </div>
            </div>
        </div>
        <div class='col-3 p-3'>
            <div class='card'>
                <div class='card-body'>
                    <a href="{{route('subject_join_book.index')}}">
                        <h4>SubjectJoinBook</h4>
                    </a>
                </div>
            </div>
        </div>
        <!-- ADD_ITEM -->
@endsection
@section('script')
@endsection
