@extends('layouts.app')

@section('title', 'People')

@section('content')
<main>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <h1>
                    <strong>
                        <span class="fas fa-users"></span>
                        People
                    </strong>
                </h1>
            </div>
        </div>

        <div style="border: 1px solid gray"></div>

        <div class="row mt-3">
            @for($i = 0; $i < $users->count(); $i++)
                @if(($i + 1) % 7)
                    @include('partials.users')
                @else
                    </div>
                    <div class="row">
                        @include('partials.users')
                @endif
            @endfor
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination  d-flex justify-content-center">
                <li class="page-item @if($users->currentPage() == 1) disabled @endif">
                    <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                @for($i = 0; $i < $users->count() / $users->perPage(); $i++)
                    <li class="page-item @if($users->currentPage() == $i + 1) active @endif"><a class="page-link" href="{{ $users->url($i + 1) }}">{{ $i + 1 }}</a></li>
                @endfor
                <li class="page-item @if(!($users->hasMorePages())) disabled @endif">
                    <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</main>
@endsection