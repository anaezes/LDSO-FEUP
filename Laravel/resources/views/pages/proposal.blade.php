@extends('layouts.app')

@section('title', 'proposal')

@section('content')

<!-- proposal Content -->
<main>
    <div class="container mb-5 mt-5">
        <div class="row">
            <div class="col-lg-11">
                <h1>
                    <strong>
                        {{ $proposal->title }}
                    </strong>
                </h1>
            </div>
            @if(Auth::check() && $proposal->idproponent == Auth::id())
                <div class="col-lg-1">
                    <h3>
                        <a href="{{ route('proposal.edit', $proposal->id) }}" style="color: LightSlateGray" data-toggle="tooltip" data-placement="bottom" title="Edit proposal"><span class="fas fa-edit"></span></a>
                    </h3>
                </div>
            @endif
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="row mt-3 d-flex align-items-center">
            <div class="col-lg-3">
               <h3>
                   <strong>
                       Faculty
                   </strong>
               </h3>
            </div>
            <div class="col-lg-9">
                <h4>
                    @forelse($proposal->faculty as $faculties)
                        {{ $faculties->facultyname }}
                    @empty
                        No associated faculties
                    @endforelse
                </h4>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="row mt-3">
            <div class="col-lg-3">
                <h3>
                    <strong>
                        Description
                    </strong>
                </h3>
            </div>
            <div class="col-lg-9" style="word-wrap: break-word">
                <h4>
                    {{ $proposal->description }}
                </h4>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="row mt-3 d-flex align-items-center">
            <div class="col-lg-3 mb-2">
                <h3>
                    <strong>
                        Winner announcement date
                    </strong>
                </h3>
                <small>
                    Date by which a winner must be selected
                </small>
            </div>
            <div class="col-lg-9">
                <h4>
                    {{ date('Y-m-d', strtotime($proposal->announcedate)) }}
                </h4>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="row mt-3 d-flex align-items-center">
            <div class="col-lg-3 mb-2">
                <h3>
                    <strong>
                        Due date
                    </strong>
                </h3>
                <small>
                    Date by which a team must submit its project
                </small>
            </div>
            <div class="col-lg-9">
                <h4>
                    {{ date('Y-m-d', strtotime($proposal->duedate)) }}
                </h4>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="row mt-3">
            <div class="col-lg-3">
                <h3>
                    <strong>
                        Proponent
                    </strong>
                </h3>
            </div>
            <div class="col-lg-9">
                <h4>
                    <a href="{{ route('profile', $proposal->user) }}" class="text-dark">
                        {{ $proposal->user->name }}
                    </a>
                </h4>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="row mt-3">
            <div class="col-lg-3">
                <h3>
                    <strong>
                        @if($proposal->skill()->count() > 1)
                            Skills
                        @else
                            Skill
                        @endif
                    </strong>
                </h3>
            </div>
            <div class="col-lg-9">
                <h4>
                    @forelse($proposal->skill()->limit(20)->get() as $skill)
                        {{ $skill->skillname }};&nbsp;
                    @empty
                        No skills needed
                    @endforelse
                </h4>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="row mt-3">
            <div class="col-lg-3">
                <h3>
                    <strong>
                        Time left
                    </strong>
                </h3>
            </div>
            <div class="col-lg-9">
                    @if($proposal->proposal_status == 'finished')
                        <h4 id="timeLeft" class="text-danger">Proposal has finished</h4>
                    @else
                        <h4 id="timeLeft" class="text-danger">{{ $timestamp }}</h4>
                    @endif
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-10">
                        <h3>
                            <strong>
                                Bids
                            </strong>
                        </h3>
                    </div>
                    <div class="col-lg-2">
                        <h4>
                            <?php
                                $winnerBid = $proposal->bids()->where('winner', true)->first();
                            ?>
                            @if($proposal->proposal_status == "finished" || $proposal->proposal_status == 'evaluated')
                                <button class="btn btn-secondary" disabled>
                                    <span>
                                        The proposal has finished
                                    </span>
                                </button>
                            @elseif($proposal->proposal_status == 'waitingApproval')
                                <button class="btn btn-secondary" disabled>
                                    <span>
                                        Proposal waiting approval
                                    </span>
                                </button>
                            @elseif(Auth::check())
                                @if($proposal->idproponent != Auth::id())
                                    @if($winnerBid != null && $winnerBid->team->user->id == Auth::id())
                                        <a href="{{ route ('bid', $winnerBid) }}" class="btn btn-primary">
                                            <span class="fas fa-paper-plane">
                                                Submit project
                                            </span>
                                        </a>
                                    @else
                                        <a href="{{ route ('createBid', $proposal) }}" class="btn btn-primary">
                                            <span class="fas fa-plus">
                                                Bid
                                            </span>
                                        </a>
                                    @endif
                                @endif
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <span>
                                        Login to Bid
                                    </span>
                                </button>
                            @endif
                        </h4>
                    </div>
                </div>
                @if(Auth::check())
                    @if($proposal->idproponent == Auth::id() || $proposal->bid_public)
                        <div class="row">
                            <div class="col-lg-2">
                                <h5>
                                    ID
                                </h5>
                            </div>
                            <div class="col-lg-4">
                                <h5>
                                    Team
                                </h5>
                            </div>
                            <div class="col-lg-4">
                                <h5>
                                    Leader
                                </h5>
                            </div>
                            <div class="col-lg-2">
                                <h5>
                                    Date
                                </h5>
                            </div>
                        </div>
                        <?php
                            $bids = $proposal->bids()->paginate(10);
                        ?>
                        @foreach($bids as $bid)
                            <hr id="hr_space" class="mt-2">
                            <div class="row mt-3">
                                <div class="col-lg-2">
                                    <h5>
                                        <a href="{{ route('bid', $bid) }}" class="text-dark">
                                            {{ $bid->id }}
                                        </a>
                                    </h5>
                                </div>
                                <div class="col-lg-4">
                                    <h5>
                                        <a href="{{ route('team.show', $bid->team) }}" class="text-dark">
                                            {{ $bid->team->teamname }}
                                        </a>
                                    </h5>
                                </div>
                                <div class="col-lg-4">
                                    <h5>
                                        <a href="{{ route('profile', $bid->team->user) }}" class="text-dark">
                                            {{ $bid->team->user->name }}
                                        </a>
                                    </h5>
                                </div>
                                <div class="col-lg-2">
                                    <h5>
                                        {{ date('Y-m-d H:i', strtotime($bid->biddate)) }}
                                    </h5>
                                </div>
                            </div>
                        @endforeach
                        @if($bids->total() / $bids->perPage() > 1)
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center mt-3">
                                    <li class="page-item @if($bids->currentPage() == 1) disabled @endif">
                                        <a class="page-link" href="{{ $bids->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </li>
                                    @for($i = 0; $i <= $bids->total() / $bids->perPage(); $i++)
                                        <li class="page-item @if($bids->currentPage() == $i+1) active @endif"><a class="page-link" href="{{ $bids->url($i+1) }}">{{ $i+1 }}</a></li>
                                    @endfor
                                    <li class="page-item @if(!($bids->hasMorePages())) disabled @endif">
                                        <a class="page-link" href="{{ $bids->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        @endif
                    @else
                        <div class="row mt-3 d-flex justify-content-center">
                            <div class="col-lg-12">
                                <h4>
                                    Bids to this proposal are not public
                                </h4>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="row mt-3 d-flex justify-content-center">
                        <div class="col-lg-12">
                            <h4>
                                Login to see the bids to this proposal
                            </h4>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

@endsection
