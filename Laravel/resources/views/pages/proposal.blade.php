@extends('layouts.app')

@section('title', 'proposal')

@section('content')

    <!-- proposal Content -->
            <main  data-id="{{$proposal, $facultyName}}">
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
                        <h5 class="modal-title" >proposal action</h5>
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
                            <td rowspan="8" colspan="2" style="width: 26.33%">
                                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        @foreach($images as $key => $image)
                                        <li data-target="#carouselExampleIndicators" data-slide-to={{$key}} @if ($key === 0) class="active" @endif></li>
                                        @endforeach
                                    </ol>
                                    <div class="carousel-inner">
                                        @foreach($images as $key => $image)
                                        <div class=@if ($key === 0) "carousel-item active" @else "carousel-item" @endif >
                                            <img class="d-block w-100" src="{{asset('../img/'.$image)}}" height="350" width="200" alt="{{$image}}" >
                                        </div>
                                        @endforeach
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </td>
                            <td style="width: 16.66%"><strong>Title</strong></td>
                            <td>{{$proposal->title}}</td>
                        </tr>
                        <tr>
                            <td><strong>Author</strong></td>
                            <td> {{$proposal->author}} </td>
                        </tr>

                        <tr>
                            <td><strong>ISBN</strong></td>
                            <td>{{$proposal->isbn}}</td>
                        </tr>

                        <tr>
                            <td><strong>faculty</strong></td>
                            <td>{{$facultyName}}</td>
                        </tr>

                        <tr>
                            <td><strong>Description</strong></td>
                            <td>{{$proposal->description}}</td>
                        </tr>
                        <tr>
                            <td><strong>Seller</strong></td>
                            <td><a class="button btn btn-sm btn-outline-secondary p-2 " href="{{ url("profile/{$proposal->user->id}") }}"><b>{{$proposal->user->name}}</b></a></td>
                        </tr>
                        <tr>
                            <td><strong>Time left: </strong>
                                <p id="timeLeft" class="text-danger">{{$timestamp}}</p>
                            </td>
                            <td><strong>Current bid: </strong>
                                <p id="currentMaxBid" class="text-success">{{$maxBid}}€</td>
                            <td>
                                <form>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            @if (Auth::check())
                                                @if(Auth::user()->id != $proposal->idseller)
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
                                        @if(Auth::user()->id != $proposal->idseller)
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
                                    @elseif ($proposal->idseller == Auth::user()->id)
                                    <button id="edit-proposal" type="submit" class="btn btn-primary col-md-6" style = "margin-top: 3px;">Edit the proposal</button>
                                    @elseif ($proposal->proposal_status != "approved")
                                    <button id="unbiddable" type="submit" disabled class="btn btn-outline-secondary col-md-6">Unable to bid</button>
                                    @else
                                    <button id="bid-box" type="submit" class="btn btn-primary col-md-6">Bid a new price</button>
                                    @endif
                                @else
                                <button id="bid-box" type="submit" disabled class="btn btn-outline-secondary col-md-10">Login to bid</button>
                                @endif

                                @if (Auth::check()&&$proposal->idseller != Auth::user()->id)
                                @if ($proposal->wishlisted == true)
                                <button id="wish-box" type="submit" class="btn btn-primary col-md-6" style = "margin-top: 3px;">Remove from wishlist</button>
                                @else
                                <button id="wish-box" type="submit" class="btn btn-primary col-md-6" style = "margin-top: 3px;">Add to wishlist</button>
                                @endif
                                @elseif (!Auth::check())
                                <button type="submit" class="btn btn-outline-secondary col-md-4" disabled>Login to wishlist</button>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>


                            </td>
                        </tr>
                    </tbody>
                </table>
              </div>
            </main>

@endsection
