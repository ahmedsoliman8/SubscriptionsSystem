@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <ul class="nav flex-column mb-4">
                    <li class="nav-item"><a class="nav-link" href="{{route('account')}}">Account</a></li>
                </ul>
                <ul class="nav flex-column mb-4">
                    <li class="nav-item"><a class="nav-link" href="{{route('account.subscriptions')}}">Subscription</a>
                    </li>
                    @if(auth()->user()->subscribed())
                        @if(!auth()->user()->subscription('default')->cancelled())
                            <li class="nav-item"><a class="nav-link" href="{{route('account.subscriptions.cancel')}}">Cancel
                                    Subscription</a></li>
                        @endif
                        @if(auth()->user()->subscription('default')->cancelled())
                            <li class="nav-item"><a class="nav-link" href="{{route('account.subscriptions.resume')}}">Resume
                                    Subscription</a></li>
                        @endif
                    @endif
                </ul>
            </div>
            <div class="col-md-9">
                @yield('account')
            </div>
        </div>
    </div>
@endsection