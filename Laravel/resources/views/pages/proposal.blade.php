@extends('layouts.app')

@section('title', 'proposal')

@section('content')

<!-- proposal Content -->
<main  data-id="{{$proposal, $facultyName, $timestamp, $bids}}">
    <div class="container mb-5 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <h1><strong>{{ $proposal->title }}</strong></h1>
            </div>
        </div>

        <div style="border: 1px solid gray" class="mt-1"></div>

        <div class="row mt-3 d-flex align-items-center">
            <div class="col-lg-3">
               <h3>
                   <strong>
                       Faculty
                   </strong>
               </h3>
            </div>
            <div class="col-lg-9">
                @foreach($proposal->faculty as $faculties)
                    <h4>
                        {{ $faculties->facultyname }}
                    </h4>
                @endforeach
            </div>
        </div>

        <div style="border: 1px solid gray" class="mt-1"></div>

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

        <div class="row mt-3">
            <div class="">

            </div>
            <div>

            </div>
        </div>

    <table class="table" style="border: none">
        <tbody style="border: none">
            <tr>
                <td colspan="2" style="border: none" >
                    <strong style="font-size: xx-large">{{$proposal->title}}</strong>
                    @if($proposal->proposal_status == "finished")
                        <strong style="font-size: xx-large">
                            <span class="badge badge-pill badge-secondary ml-5">Finished</span>
                        </strong>
                    @endif
                </td>
            </tr>
            
            <tr>
                <td style="border: none; width: 150px;"><strong>Faculty</strong></td>
                <td style="border: none">{{$facultyName}}</td>
            </tr>

            <tr>
                <td><strong>Description</strong></td>
                <td>{{$proposal->description}}</td>
            </tr>

            <tr>
                <td><strong>Due date</strong></td>
                <td>{{$proposal->duedate}}</td>
            </tr>

            <tr>
                <td><strong>Announce team winner</strong></td>
                <td>{{$proposal->announcedate}}</td>
            </tr>
            <tr>
                <td><strong>Proponent</strong></td>
                <td><a class="button btn btn-sm btn-outline-secondary p-2 " href="{{ url("profile/{$proposal->user->id}") }}">
                    <b>
                        <i class="fa fa-user"></i>
                        <p> {{$proposal->user->name}}</p>
                    </b></a></td>
            </tr>
            <tr>
                <td><strong>Skills</strong></td>
                <td>
                @foreach ($proposal->skills as $skill)
                    {{$skill->skillname}}
                @endforeach
                </td>
            </tr>
            <tr>
                <td><strong>Time left: </strong></td>
                @if($proposal->proposal_status == "finished")
                <td id="timeLeft" class="text-danger">Proposal has finished</td>
                @else
                <td id="timeLeft" class="text-danger">{{$timestamp}}</td>
                @endif
            </tr>
            <tr>
                <td colspan="2" style="border: none; text-align: right">
                    @if (Auth::check())
                        @if ($proposal->idproponent == Auth::user()->id)

                            @if($proposal->proposal_status != "finished" && $proposal->proposal_status != "evaluated")
                                <a id="edit-proposal" href="{{ route('proposal.edit', $proposal->id) }}" class="btn btn-primary col-md-6 mt-3" style="width: 250px">
                                    Edit the proposal
                                </a>
                            @else
                                <button id="unbiddable" type="submit" disabled class="btn btn-outline-secondary col-md-6 mt-3" style="width: 250px">The proposal has finished</button>
                            @endif
                        @elseif ($proposal->proposal_status == "waitingApproval")
                    <button id="unbiddable" type="submit" disabled class="btn btn-outline-secondary col-md-6 mt-3" style="width: 250px">Unable to bid</button>
                        @elseif ($proposal->bids()->where('winner', true)->first() != null && $proposal->bids()->where('winner', true)->first()->team->user->id == Auth::user()->id && $proposal->proposal_status != "evaluated")
                            <a href="{{ route('bid', $proposal->bids()->where('winner', true)->first()->id) }}" class="btn btn-primary btn-lg my-2 mx-3 jumbotron-buttons">Submit Project</a>
                        @elseif ($proposal->proposal_status != "evaluated")
                            <a href="{{ route ('createBid', ['id'=>$proposal->id])}}" class="btn btn-primary btn-lg my-2 mx-3 jumbotron-buttons">Bid</a>
                        @endif
                    @else
                    <button id="bid-box" type="submit" disabled class="btn btn-outline-secondary col-md-6 mt-3" style="width: 250px">Login to bid</button>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
        <table class="table" style= "border : none">
                <tr>
                    <td style="border: none" > <strong style="font-size: x-large">Bids</strong></td>
                </tr>

            @if (Auth::check())
                @if ($proposal->idproponent == Auth::user()->id || $proposal->bid_public)
                    <tr>
                        <td> Bid </td>
                        <td> Team </td>
                        <td> Leader </td>
                        <td> Date  </td>
                        <?php
                            $winner = $proposal->bids()->where('winner', true)->first();
                            if($winner != null) {
                        ?>
                            <td> Winner </td>
                        <?php } ?>
                    </tr>
                    @foreach ($bids as $bid)
                        <tr>
                            <td>
                                <a style="border:none" class="button btn btn-sm btn-outline-secondary p-2 "
                                    href="{{ url("bid/{$bid->id}") }}">
                                    <b> <p> {{$bid->id}}</p> </b>
                                </a>
                            </td>
                            <td>
                                <strong>{{$bid->teamname}} </strong>
                            </td>
                            <td>
                                <a style="border:none" class="button btn btn-sm btn-outline-secondary p-2 "
                                    href="{{ url("profile/{$bid->teamleaderid}") }}">
                                    <b> <p> {{$bid->teamleadername}}</p> </b>
                                </a>
                            </td>
                            <td>
                                {{date("d/m/Y H:i", strtotime($bid->biddate))}}
                            </td>
                            @if($winner != null && $winner->id == $bid->id)
                                <td>
                                    <span class="badge badge-secondary">Winner</span>                                                
                                </td>
                            @else
                                <td>
                                    <!-- Display nothing but dont break borders and page layout -->
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                <tr>
                    <td> Bids not available!</td>
                </tr>
                @endif
            @endif
            </tbody>
        </table>
    </div>
</main>

@endsection
