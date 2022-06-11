@extends('layouts.account')
@section('account')
    <div class="card">
        <div class="card-header">{{ __('Swap') }}</div>
        <div class="card-body">
            <form action="{{route('account.subscriptions.swap')}}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="plan" class="form-label">Plan</label>
                    <select id="plan" name="plan" class="form-control">
                        @foreach($plans as $plan)
                            <option value="{{$plan->slug}}">{{$plan->title}}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Swap</button>
            </form>
        </div>
    </div>
@endsection
