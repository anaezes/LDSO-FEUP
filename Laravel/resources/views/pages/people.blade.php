@extends('layouts.app')

@section('title', 'People')

@section('content')
<main>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <h4>
                    <i class="fas fa-users"></i>
                    People
                </h4>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

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
            <ul class="pagination justify-content-center mt-3">
                <li class="page-item @if($users->currentPage() == 1) disabled @endif">
                    <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                @for($i = 0; $i < $users->total() / $users->perPage(); $i++)
                    <li class="page-item @if($users->currentPage() == $i+1) active @endif"><a class="page-link" href="{{ $users->url($i+1) }}">{{ $i+1 }}</a></li>
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