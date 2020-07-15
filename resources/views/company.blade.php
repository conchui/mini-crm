@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="container mb-5">
                <a href="{{ url('/') }}" class="btn btn-secondary mb-5">Back</a>
                
                <br/>
                <img src="{{ url('/storage/').'/'.$companyDetails['logo'] }}">
                <br/>
                <strong>{{ $companyDetails['name'] }}</strong>
                <br/>
                <a href="mailto:{{ $companyDetails['email'] }}">{{ $companyDetails['email'] }}</a>
                <br/>
                <a href="{{ $companyDetails['website'] }}">{{ $companyDetails['website'] }}</a>
            </div>

            <div class="app">
                <employees-component 
                    base_url="{{ url('/') }}" 
                    company_id="{{ $companyDetails['id'] }}">
                </employees-component>
            </div>
        </div>
    </div>
</div>
@endsection
