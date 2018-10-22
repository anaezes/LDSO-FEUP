<div class="list-group-item list-group-item-action text-muted mb-2" id="cr-{{$proposal->id}}">
    <div class="container">
        <div class="row">
            <a href="{{ url('auction/') }}/{{$auction->id}}" class="col-lg-6 align-self-center text-left p-2 text-dark lead">
               {{ $proposal->title }}
            </a>
            <div class="col-lg-4 text-center align-self-center p-3 text-danger">
                <a onclick="moderatorAction('approve_creation',{{$proposal->id}})"><i class="fas fa-check fa-2x btn btn-success align-self-center p-2 m-2"></i></a>
                <a onclick="moderatorAction('remove_creation',{{$proposal->id}}) "><i class="fas fa-ban fa-2x btn btn-danger align-self-center p-2 m-2"></i></a>
            </div>
        </div>
    </div>
</div>
