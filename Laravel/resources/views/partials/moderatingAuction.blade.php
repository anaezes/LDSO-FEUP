<div class="list-group-item list-group-item-action text-muted mb-2" id="cr-{{$auction->id}}">
    <div class="container">
        <div class="row">
            <div class="col-sm-auto img-fluid media-object align-self-center ">
                <a href="{{ url('auction/')}}/{{$auction->id}}"><img class="width100" src="{{'img/book.png'}}" alt="book cover"></a>
            </div>
            <a href="{{ url('auction/') }}/{{$auction->id}}" class="col-lg-6 align-self-center text-left p-2 text-dark lead">
               {{ $auction->title }}
            </a>
            <div class="col-lg-4 text-center align-self-center p-3 text-danger">
                <a onclick="moderatorAction('approve_creation',{{$auction->id}})"><i class="fas fa-check fa-2x btn btn-success align-self-center p-2 m-2"></i></a>
                <a onclick="moderatorAction('remove_creation',{{$auction->id}}) "><i class="fas fa-ban fa-2x btn btn-danger align-self-center p-2 m-2"></i></a>
            </div>
        </div>
    </div>
</div>
