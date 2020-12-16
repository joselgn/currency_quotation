@extends('layouts.theme')

@section('content')
    <div class="alert alert-danger">
            <h2>Ops! Ocorreu um erro. </h2>

            <h4>{{ $message }}</h4>
    </div>
@endsection
