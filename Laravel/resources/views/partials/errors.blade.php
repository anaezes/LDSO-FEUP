@if ($errors->any())
<!-- Modal -->
<div id="myModalError" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <i class="fa fa-times"></i>
        <h4 class="modal-title align-self-center">  Errors</h4>
      </div>
      <div class="modal-body">
            @foreach ($errors->all() as $error)
                <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endif
