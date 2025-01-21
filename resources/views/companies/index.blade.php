@extends('layouts.app')

@section('content')
    <h1>Companies</h1>
    <ul>
    @foreach($companies as $company)
        <li>{{ $company->name }}</li>
    @endforeach
    </ul>
@endsection
