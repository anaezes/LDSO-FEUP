<div class="col-md-3 auctionItem"  data-id="{{$auction->id}}">
    <a href="{{ url('auction/')}}/{{$auction->id}}" class="list-group-item-action">
        <div class="card mb-4 box-shadow">
            <div class="col-md-6 img-fluid media-object align-self-center ">
                <img class="width100" src="{{ 'img/' . $auction->image }}" alt="{{$auction->image}}">
            </div>
            <div class="card-body">
                <p class="card-text text-center hidden-p-md-down font-weight-bold" style="font-size: larger">{{ $auction->title }} </p>
                <p class="card-text text-center hidden-p-md-down">By {{ $auction->author }} </p>
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fas fa-star btn btn-sm text-primary"></i>
                    <small class="text-success">{{$auction->bidValue}}</small>
                    <small class="text-danger">{{$auction->timestamp}}</small>
                </div>
            </div>
        </div>
    </a>
</div>
