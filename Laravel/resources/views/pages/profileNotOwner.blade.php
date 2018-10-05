    <!-- Profile Content -->
    <main  data-id="{{$user}}">
        <div class="container-fluid bg-white">
            <div class="bg-white mb-0 mt-4 panel">
                <h5><i class="fa fa-user-circle"></i> {{$user->username}}'s profile</h5>
            </div>
            <hr id="hr_space" class="mt-2">
            <div class="row">
                <div class="col-lg-2 col-sm-6 text-center mb-4">
                    <img class="img-fluid d-block mx-auto" src="{{asset('avatars/default.png')}}" alt="{{$user->name}} photo">
                </div>
                <table class="table table-striped col-lg-4">
                    <tbody>
                        <tr>
                            <td style="width:16.33%">
                                <strong>Full name</strong>
                            </td>
                            <td>
                                {{$user->name}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Email address</strong>
                            </td>
                            <td>
                                {{$user->email}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Phone Number</strong>
                            </td>
                            <td>
                                {{$user->phone}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Country</strong>
                            </td>
                            <td>
                                {{$user->country->countryname}}
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>


            <!--feedback-->
            <div class = "container-fluid bg-white">
                <div class="bg-white mb-0 mt-3 mb-3 panel">
                    <h5><i class="fa fa-comments"></i> Feedback</h5>
                </div>
                <form id="feedbackform">
                    <div class="btn-group mb-3" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-success">
                            <i class="fa fa-thumbs-up btn btn-success" alt="good feedback"></i>
                        </button>
                        <button type="button" class="btn btn-danger">
                            <i class="fa fa-thumbs-down btn btn-danger" alt="bad feedback"></i>
                        </button>
                    </div>
                    <div class="form-group">
                        <textarea rows="3" cols="30" class="form-control" placeholder="Your feedback"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary col-md-12">Post your feedback</button>
                        </div>
                    </div>

                </form>
                <div class = "list-group panel" id="myfeedback">

                </div>

            </div>
      </div>
  	</main>
