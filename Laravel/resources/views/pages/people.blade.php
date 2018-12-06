@extends('layouts.app')

@section('title', 'People')

@section('content')
<main>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <h1>
                    <strong>
                        People
                    </strong>
                </h1>
            </div>
        </div>

        <div style="border: 1px solid gray"></div>

        <div class="row mt-3">
            @for($i = 0; $i < $users->count(); $i++)
                @if(($i + 1) % 5)
                    @include('partials.users')
                @else
                    </div>
                    <div class="row">
                        @include('partials.users')
                @endif
            @endfor
        </div>
        {{ $users->links() }}
    </div>
</main>
@endsection