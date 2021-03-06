

<!-- Admin Info -->
@if (Auth::check())
@if (Auth::user()->users_status=="admin" )
<!-- Moderator additional info alert box -->
<div class="container p-5">
   <div class="alert alert-secondary" role="alert">
      <h5>
         <i class="fas fa-info"></i>
         Administrator additional info:
      </h5>
      <hr>
      <ul class="list-group">
         <li class="list-group-item bg-secondary text-white"><b>Status:</b> {{$user->users_status}}</li>
         <li class="list-group-item"><b>Date created:</b> {{$user->datecreated}}</li>
         <li class="list-group-item bg-secondary text-white"><b>Date suspended:</b> {{$user->datesuspended}}</li>
         <li class="list-group-item"><b>Date banned:</b> {{$user->datebanned}}</li>
         <li class="list-group-item bg-secondary text-white"><b>Date terminated:</b> {{$user->dateterminated}}</li>
      </ul>
   </div>
</div>
@endif
@endif
<!-- Profile Content -->
<main  data-id="{{$user}}">
   <div class="container-fluid bg-white">
      <div class="bg-white mb-0 mt-4 panel">
         <h5><i class=" co-lg-6 fa fa-user-circle"></i> {{$user->username}}&rsquo;s profile </h5>
      </div>
      <hr id="hr_space" class="mt-2">
      <div class="row">
         <div class="col-lg-2 col-sm-6 text-center mb-5">
            <img class="img-thumbnail d-block mx-auto" src="{{asset('img/'.$image)}}" alt="{{$user->name}} photo">
         </div>
         <table class="table table-striped col-lg-9">
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
                     @if (Auth::check() && Auth::user()->id != $user->id)
                     <div class="col-md-3" style = "display: inline-block;">
                        <a class="btn " data-toggle="modal" data-target="#myModalMail">
                        <i class="fa fa-envelope"></i> Contact
                        </a>
                     </div>
                     @endif
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
                     <strong>Faculty</strong>
                  </td>
                  <td>
                     {{$user->faculty->facultyname}}
                  </td>
               <tr>
                   <td>
                       <strong>Skills</strong>
                   </td>
                   <td>

                       @foreach ($user->skills as $skill)
                       <p> {{ $skill->skillname }}  </p>
                       @endforeach
                     
                   </td>
               </tr>
            </tbody>
         </table>
      </div>
      @if(Auth::check() && Auth::User()->id == $user->id)
      <div class="list-group">
         <div class="container">
            <div class="row">
               <div class="col-lg-6">
                  <a class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#myModalEdit">
                  <i class="fa fa-user-plus"></i> Edit Info
                  </a>
               </div>
            </div>
         </div>
      </div>
      @endif

      @if(Auth::check())
         <div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalEditLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="myModalEditLabel">Edit Info</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <form method="POST" action="{{ route('profile.edit', ['id' => Auth::user()->id]) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                           <label for="InputName">Name</label>
                           <input class="form-control" type="text" id="InputName" name="name" placeholder="{{$user->username}}" value="{{ old('name') }}">
                        </div>

                        <div class="form-group">
                           <label for="InputEmail1">Email address</label>
                           <input class="form-control" type="email" name="email" placeholder="{{$user->email}}" value="{{ old('email') }}">
                        </div>

                     <div class="form-group">
                           <label for="Inputidfaculty">faculty</label>
                           <select class="form-control" id="Inputidfaculty" name="idfaculty">
                              <option value="1" {{ old('idfaculty') == 1 ? 'selected' : '' }}>Faculty of Architecture</option>
                              <option value="2" {{ old('idfaculty') == 2 ? 'selected' : '' }}>Faculty of Fine Arts</option>
                              <option value="3" {{ old('idfaculty') == 3 ? 'selected' : '' }}>Faculty of Sciences</option>
                              <option value="4" {{ old('idfaculty') == 4 ? 'selected' : '' }}>Faculty of Nutrition and Food Science</option>
                              <option value="5" {{ old('idfaculty') == 5 ? 'selected' : '' }}>Faculty of Sports</option>
                              <option value="6" {{ old('idfaculty') == 6 ? 'selected' : '' }}>Faculty of Law</option>
                              <option value="7" {{ old('idfaculty') == 7 ? 'selected' : '' }}>Faculty of Economics</option>
                              <option value="8" {{ old('idfaculty') == 8 ? 'selected' : '' }}>Faculty of Engineering</option>
                              <option value="9" {{ old('idfaculty') == 9 ? 'selected' : '' }}>Faculty of Pharmacy</option>
                              <option value="10" {{ old('idfaculty') == 10 ? 'selected' : '' }}>Faculty of Arts</option>
                              <option value="11" {{ old('idfaculty') == 11 ? 'selected' : '' }}>Faculty of Medicine</option>
                              <option value="12" {{ old('idfaculty') == 12 ? 'selected' : '' }}>Faculty of Dental Medicine</option>
                              <option value="13" {{ old('idfaculty') == 13 ? 'selected' : '' }}>Faculty of Psychology and Education Science</option>
                              <option value="14" {{ old('idfaculty') == 14 ? 'selected' : '' }}>Abel Salazar Institute of Biomedical Science</option>
                              <option value="15" {{ old('idfaculty') == 15 ? 'selected' : '' }}>Porto Business School</option>
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="phone1">Phone Number</label>
                           <input class="form-control" id="phone1" name="phone" type="tel" placeholder="{{$user->phone}}" value="{{ old('phone') }}">
                        </div>
                        <div class="form-group">
                           <label>Your profile picture</label>
                           <input id="image" name="image" class="form-control" type="file" accept="image/*">
                           @if ($errors->has('images'))
                           <span class="error">
                           {{ $errors->first('images') }}
                           </span>
                           @endif
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mb-4">Save any new changes</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal fade" id="myModalMail" tabindex="-1" role="dialog" aria-labelledby="myModalMailLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="myModalMailLabel">Contact {{$user->name}}</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-15">
                           <form id="contactForm" class="ml-5 mr-4" method="POST" action="{{ route('contact') }}" enctype="multipart/form-data">
                              {{ csrf_field() }}
                              <div class="messages"></div>
                              <div class="controls">
                                 <div class="row">
                                    <div class="col">
                                       <div class="form-group">
                                          <label for="name">Name</label>
                                          <input id="name" type="text" name="name" class="form-control" placeholder="Enter your name" required="required" data-error="Userame is required.">
                                          <div class="help-block with-errors"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col">
                                       <div class="form-group">
                                          <label for="email">E-mail</label>
                                          <input id="email" type="email" name="email" class="form-control" placeholder="Enter your e-mail" required="required" data-error="Valid e-mail is required.">
                                          <div class="help-block with-errors"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label for="message">Message</label>
                                          <textarea id="message" name="message" class="form-control" placeholder="Enter your message" rows="4" required="required" data-error="Please, enter the message."></textarea>
                                          <div class="help-block with-errors"></div>
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <button onclick="submitContactMessage()" id="contactSubmitButton" class="btn btn-send text-white border border-success bg-success"> Send Message</button>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                    </div>
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
     @endif
   </div>
</main>
