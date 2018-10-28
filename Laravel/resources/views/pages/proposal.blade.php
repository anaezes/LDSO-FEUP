@extends('layouts.app')

@section('title', 'proposal')

@section('content')

    <!-- proposal Content -->
            <main  data-id="{{$proposal, $facultyName, $maxBid, $timestamp}}">
              <div class="container p-5">

                <div id="bidResult" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 id="bidResultHeader" class="modal-title align-self-center">.</h4>
                      </div>
                      <div class="modal-body">
                            <p id="bidResultBody" class="alert alert-danger"></p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                      </div>
                    </div>

                  </div>
                </div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td style="width: 16.66%"><strong>Title</strong></td>
                            <td>{{$proposal->title}}</td>
                        </tr>

                        <tr>
                            <td><strong>Faculty</strong></td>
                            <td>{{$facultyName}}</td>
                        </tr>

                        <tr>
                            <td><strong>Description</strong></td>
                            <td>{{$proposal->description}}</td>
                        </tr>
                        <tr>
                            <td><strong>Proponent</strong></td>
                            <td><a class="button btn btn-sm btn-outline-secondary p-2 " href="{{ url("profile/{$proposal->user->id}") }}"><b>{{$proposal->user->name}}</b></a></td>
                        </tr>
                        <tr>
                            <td><strong>SKILLS</strong></td>
                            @foreach ($proposal->skills as $skill)
                            <td>{{ $skill->skillname}}</td>
                            @endforeach
                        </tr>
                        <tr>
                           <td><strong>Time left: </strong>
                                <p id="timeLeft" class="text-danger">{{$timestamp}}</p>
                            </td>
                            <td><strong>Current bid: </strong>
                                <p id="currentMaxBid" class="text-success">0€</td>
                            <td>
                                <form>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            @if (Auth::check())
                                                @if(Auth::user()->id != $proposal->idproponent)
                                                    @if($proposal->proposal_status == "approved")
                                                    <input id="currentBid" type="number" min="0.00" placeholder="0.00" step="0.01" class="form-control">
                                                    @else
                                                    <input id="currentBid" type="number" min="0.00" placeholder="0.00" disabled step="0.01" class="form-control">
                                                    @endif
                                                @endif
                                            @else
                                            <input id="currentBid" disabled type="number" min="0.00" placeholder="0.00" step="0.01" class="form-control">
                                            @endif

                                        </div>
                                    </div>
                                </form>
                            </td>
                            <td>
                                @if (Auth::check())
                                    @if ($proposal->idproponent == Auth::user()->id)
                                    <button id="edit-proposal" type="submit" class="btn btn-primary col-md-6" style = "margin-top: 3px;">Edit the proposal</button>
                                    @elseif ($proposal->proposal_status != "approved")
                                    <button id="unbiddable" type="submit" disabled class="btn btn-outline-secondary col-md-6">Unable to bid</button>
                                    @else
                                    <button id="bid-box" type="submit" class="btn btn-primary col-md-6">Bid a new price</button>
                                    @endif
                                @else
                                <button id="bid-box" type="submit" disabled class="btn btn-outline-secondary col-md-10">Login to bid</button>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
              </div>
            </main>

@endsection
