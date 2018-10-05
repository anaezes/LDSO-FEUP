@extends('layouts.app')

@section('title', 'Moderator Panel')

@section('content')

        <!-- Modification changes modal -->
        <div class="modal fade" id="auctionModificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLongTitle">Description changes</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div id="bookInfo" class="mb-4">
                <h4>
                  <i class="fas fa-book"></i> Title
                </h4>
                  <div id="bookTitle" class="lead">
                  </div>
                </div>
                <h4>
                    <i class="fas fa-quote-left"></i>
                    Old description
                </h4>
                <hr>
                <div id="oldDescription" class="alert-danger mb-3">
                Getting data from server...
                </div>
                <h4>
                    <i class="fas fa-quote-right"></i>
                    New description
                </h4>
                <hr>
                <div id="newDescription" class="alert-success">
                Getting data from server...
                </div>
              </div>
              <div class="modal-footer">
                <button onclick="" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button onclick="" id="removeBtn" type="button" class="btn btn-danger" data-dismiss="modal">Remove</button>
                <button onclick="" id="approveBtn" type="button" class="btn btn-success" data-dismiss="modal">Approve</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Approve new auctions -->
        <div class="container-fluid bg-white">
            <!-- Title-->
            <div class="bg-white mb-0 mt-4 panel">
                <h5 class="mb-4">
                    <i class="fas fa-check-square"></i> Pending Auction Requests</h5>
            </div>

              <!-- Tab design -->
              <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Creation Requests</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Modification Requests</a>
                </li>
              </ul>
              <!-- Tab content -->
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <!-- Approve new auctions list -->
                    @each('partials.moderatingAuction', $auctions, 'auction')
                    @empty($auctions)
                      <div class="alert alert-info" role="alert">
                      Nothing to approve here.
                      </div>
                    @endempty
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <!-- Approve modifying auctions list -->
                    @foreach ($auction_modifications as $auction_modification)

                      {{-- Get the data from of auctions to modify --}}
                      @foreach ($auctions_to_mod as $auction_to_mod)
                          @break ($auction_modification->idapprovedauction == $auction_to_mod->id)
                      @endforeach

                      @include('partials.auctionModification',['auction_to_mod'=>$auction_to_mod,'auction_modification'=>$auction_modification])
                    @endforeach

                    @empty($auction_modifications)
                      Nothing to approve here.
                    @endempty

                </div>
              </div>
        </div>

@endsection