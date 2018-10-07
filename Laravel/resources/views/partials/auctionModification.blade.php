<div class="list-group-item list-group-item-action text-muted mb-2" id="mr-{{$proposal_modification->idapprovedproposal}}">
    <div class="container">
        <div class="row">
            <div class="col-sm-auto img-fluid media-object align-self-center ">
                <a href="{{ url('proposal/')}}/{{$proposal_modification->idapprovedproposal}}"><img class="width100" src="{{ 'img/book.png' }}" alt="book cover"></a>
            </div>
            <a href="{{ url('proposal/') }}/{{$proposal_modification->idapprovedproposal}}" class="col-lg-6 align-self-center text-left p-2 text-dark lead">
            {{$proposal_to_mod->title}}
            </a>
            <div class="col-lg-4 text-center align-self-center p-3 text-danger">
                <button onclick="moderatorAction('get_new_description',{{$proposal_to_mod->id}},{{$proposal_modification->id}})" data-toggle="modal" data-target="#proposalModificationModal" type="button" class="btn btn-primary"><h5 class="align-self-center "><i class="far fa-file-alt fa-2x btn btn-primary "></i> See changes </h5></button>
            </div>
        </div>
    </div>
</div>
