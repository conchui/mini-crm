@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="app">
                <companies-component base_url="{{ url('/') }}"></companies-component>
            </div>
        </div>
    </div>
</div>
@endsection
