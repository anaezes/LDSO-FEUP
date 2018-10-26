@extends('layouts.app')

@section('title', 'proposal')

@section('content')

    <!-- proposal Content -->
            <main  data-id="{{$proposal, $facultyName, $maxBid, $timestamp}}">
              <div class="container p-5">
                @if (Auth::check())
                    @if (Auth::user()->users_status=="moderator" ||Auth::user()->users_status=="admin"  )
                        <!-- Moderator additional info alert box -->
                        <div class="alert alert-secondary" role="alert">
                            <h5>
                                <i class="fas fa-info"></i>
                                Moderating additional info:
                            </h5>
                            <hr>
                            <ul class="list-group">
                                <li class="list-group-item bg-secondary text-white"><b>Status:</b> {{$proposal->proposal_status}}</li>
                                <li class="list-group-item"><b>Date approved:</b> {{$proposal->dateapproved}}</li>
                                <li class="list-group-item bg-secondary text-white"><b>Date removed:</b> {{$proposal->dateremoved}}</li>
                            </ul>
                        </div>
                        <!-- Moderator remove proposal modal pop-up -->
                        <div class="modal fade" id="removeproposalModal" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" >Proposal action</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                @if ($proposal->proposal_status!="removed")
                                Are you sure you want to mark this proposal as removed?
                                @else
                                Do you want to undo the remove?
                                @endif
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                @if ($proposal->proposal_status!="removed")
                                <button type="button" class="btn btn-danger" id="mod_remove_proposal" onclick="moderatorAction('remove_proposal',{{$proposal=>id}})">Yes</button>
                                @else
                                <button type="button" class="btn btn-success" id="mod_restore_proposal" onclick="moderatorAction('restore_proposal',{{$proposal=>id}})">Yes</button>
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                    @endif
                @endif
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
                                    @if (Auth::user()->users_status=="moderator")
                                        @if(Auth::user()->id != $proposal->idproponent)
                                             <button id="bid-box" type="submit" class="btn btn-primary col-md-6">Bid a new price</button>
                                        @else
                                        <button id="edit-proposal" type="submit" class="btn btn-primary col-md-6">Edit the proposal</button>
                                        @endif
                                        <!-- Moderator remove proposal button -->
                                        @if($proposal->proposal_status!="removed")
                                        <button id="mod_remove_proposal" data-toggle="modal" data-target="#removeproposalModal" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                        @else
                                        <button id="mod_remove_proposal" data-toggle="modal" data-target="#removeproposalModal" class="btn btn-success"><i class="fas fa-undo"></i></button>
                                        @endif
                                    @elseif ($proposal->idproponent == Auth::user()->id)
                                    <button id="edit-proposal" type="submit" class="btn btn-primary col-md-6" style = "margin-top: 3px;">Edit the proposal</button>
                                    @elseif ($proposal->proposal_status != "approved")
                                    <button id="unbiddable" type="submit" disabled class="btn btn-outline-secondary col-md-6">Unable to bid</button>
                                    @else
                                    <button id="bid-box" type="submit" class="btn btn-primary col-md-6">Bid a new price</button>
                                    @endif
                                @else
                                <button id="bid-box" type="submit" disabled class="btn btn-outline-secondary col-md-10">Login to bid</button>
                                @endif

                                @if (Auth::check()&&$proposal->idproponent != Auth::user()->id)
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
