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
                <table class="table" style="border: none">
                    <tbody style="border: none">
                        <tr>
                            <td colspan="2" style="border: none" > <strong style="font-size: xx-large">{{$proposal->title}}</strong></td>
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
                                <td id="timeLeft" class="text-danger">{{$timestamp}}</td>
                        </tr>
                        <!--tr>
                            <td style="width: 150px"><strong>Current bid: </strong></td>
                               <td id="currentMaxBid" class="text-success">0€</td>
                            <td>
                                <form>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            @if (Auth::check())
                                                @if(Auth::user()->id != $proposal->idproponent)
                                                    @if($proposal->proposal_status == "approved")
                                                    <input id="currentBid" ty
Copyright pe="number" min="0.00" placeholder="0.00" step="0.01" class="form-control">
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
                        </tr-->
                        <tr>
                            <td colspan="2" style="border: none; text-align: right">
                                @if (Auth::check())
                                    @if ($proposal->idproponent == Auth::user()->id)
                                    <button id="edit-proposal" type="submit" class="btn btn-primary col-md-6" style = "margin-top: 3px; width: 200px">Edit the proposal</button>
                                    @elseif ($proposal->proposal_status != "approved")
                                    <button id="unbiddable" type="submit" disabled class="btn btn-outline-secondary col-md-6">Unable to bid</button>
                                    @else
                                    <a href="{{ route ('createBid', ['id'=>$proposal->id])}}" class="btn btn-primary btn-lg my-2 mx-3 jumbotron-buttons">Bid</a>
                                    @endif
                                @else
                                <button id="bid-box" type="submit" disabled class="btn btn-outline-secondary col-md-10">Login to bid</button>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                @if (Auth::check())
                    @if ($proposal->idproponent == Auth::user()->id)
                    <table class="table" style= "border : none">
                        <tbody style="border : none">
                            <tr>
                                <td colspan="2" style="border: none" > <strong style="font-size: x-large">Bids</strong></td>
                            </tr>
                            <tr>
                            
                            <tr> 
                        </tbody>
                    </table>
                    @endif
                @endif
                
              </div>
            </main>

@endsection
