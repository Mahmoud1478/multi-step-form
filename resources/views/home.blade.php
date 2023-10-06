@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('You are logged in!') }}
                        @php($step = auth()->user()->step()->where('state' ,  App\Enums\Step\StateEnum::UNCOMPLETED)->first())
                        @if($step)
                            <div class="d-flex mt-5 align-items-center justify-content-between alert alert-warning">
                                <span>You have uncompleted subject !!</span>
                                <a href="{{ route('subjects.create',$step->id) }}" class="btn btn-primary">@lang('globals.continue')</a>
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
