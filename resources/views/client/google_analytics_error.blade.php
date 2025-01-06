@extends('layouts.app')
@section('content')

    <div class="content">  
        @if(Auth::user()->role_type==2)
            <!--Start: Xero Data-->
            <div class="card col-md-12">
                <div class="card-header text-start ">
                    <h3 class="text-primary"> Google Analytics  </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger mb-2">You have not connected any account yet, please connect your Google Analytics account.
                        <p class="mb-2">
                            <a href="{{ url('my_business#visit_link') }}" class="">
                                Visit here
                            </a>
                            to add relevant details to connect Google Analytics account.
                        </p>
                    </div>
                </div>
            </div> 
        @endif
    </div>
        
@endsection