

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
                     @if (Auth::user()->id != $user->id)
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
                     <strong>Paypal e-mail</strong>
                  </td>
                  <td>
                     {{$user->paypalemail}}
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
      @if(Auth::User()->id == $user->id)
      <div class="list-group">
         <div class="container">
            <div class="row">
               <div class="col-lg-6">
                  <a class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#myModalEdit">
                  <i class="fa fa-user-plus"></i> Edit Info
                  </a>
               </div>
               <div class="col-lg-6">
                  <a class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#myModalPassword">
                  <i class="fa fa-user-plus"></i> Change Password
                  </a>
               </div>
            </div>
         </div>
      </div>
      @endif
      <!--feedback-->
      <div class = "container-fluid bg-white">
         <div class="bg-white mb-0 mt-3 mb-3 panel">
            <h5><i class="fa fa-comments"></i> Feedback</h5>
         </div>
         @if(Auth::User()->id !== $user->id)
         <form id="feedbackform">
            <div class="btn-group mb-3" role="group" aria-label="Basic example">
               <button type="button" onclick="setLike()" class="btn btn-success">
               <i class="fa fa-thumbs-up btn btn-success"></i>
               </button>
               <button type="button" onclick="setUnlike()" class="btn btn-danger">
               <i class="fa fa-thumbs-down btn btn-danger"></i>
               </button>
            </div>
            <div class="form-group">
               <textarea rows="3" cols="30" class="form-control" id = "left-feedback" placeholder="Your feedback" required></textarea>
            </div>
            <div class="form-row">
               <div class="form-group col-md-12">
                  <span onclick="postFeedback({{Auth::user()->id}})" class="btn btn-primary col-md-12">Post your feedback</span>
               </div>
            </div>
         </form>
         @endif
         <div class = "list-group panel" id="myfeedback"  style = "margin-bottom: 20px;">
         </div>
      </div>
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
                        <label for="InputAge">Age</label>
                        <input class="form-control" id="InputAge" name="age" type="number" placeholder="{{$user->age}}" value="{{ old('age') }}">
                     </div>
                     <div class="form-group">
                        <label for="InputEmail1">Email address</label>
                        <input class="form-control" type="email" name="email" placeholder="{{$user->email}}" value="{{ old('email') }}">
                     </div>
                     <div class="form-group">
                        <label for="address1">Address</label>
                        <input class="form-control" id="address1" name="address" type="text" placeholder="{{$user->address}}" value="{{ old('address') }}">
                     </div>
                     <div class="form-group">
                        <label for="postalcode1">Postal Code</label>
                        <input class="form-control" id="postalcode1" name="postalcode" type="text" placeholder="{{$user->postalcode}}" value="{{ old('postalcode') }}">
                     </div>
                     <div class="form-group">
                        <label for="Inputidcountry">Country</label>
                        <select class="form-control" id="Inputidcountry" name="idcountry">
                           <option selected value> -- select an option -- </option>
                           <option value="1" {{ old('idcountry') == 1 ? 'selected' : '' }}>Austria</option>
                           <option value="2" {{ old('idcountry') == 2 ? 'selected' : '' }}>Italy</option>
                           <option value="3" {{ old('idcountry') == 3 ? 'selected' : '' }}>Belgium</option>
                           <option value="4" {{ old('idcountry') == 4 ? 'selected' : '' }}>Latvia</option>
                           <option value="5" {{ old('idcountry') == 5 ? 'selected' : '' }}>Bulgaria</option>
                           <option value="6" {{ old('idcountry') == 6 ? 'selected' : '' }}>Lithuania</option>
                           <option value="7" {{ old('idcountry') == 7 ? 'selected' : '' }}>Croatia</option>
                           <option value="8" {{ old('idcountry') == 8 ? 'selected' : '' }}>Luxembourg</option>
                           <option value="9" {{ old('idcountry') == 9 ? 'selected' : '' }}>Cyprus</option>
                           <option value="10" {{ old('idcountry') == 10 ? 'selected' : '' }}>Malta</option>
                           <option value="11" {{ old('idcountry') == 11 ? 'selected' : '' }}>Czech Republic</option>
                           <option value="12" {{ old('idcountry') == 12 ? 'selected' : '' }}>Netherlands</option>
                           <option value="13" {{ old('idcountry') == 13 ? 'selected' : '' }}>Denmark</option>
                           <option value="14" {{ old('idcountry') == 14 ? 'selected' : '' }}>Indonesia</option>
                           <option value="15" {{ old('idcountry') == 15 ? 'selected' : '' }}>Poland</option>
                           <option value="16" {{ old('idcountry') == 16 ? 'selected' : '' }}>Estonia</option>
                           <option value="17" {{ old('idcountry') == 17 ? 'selected' : '' }}>Portugal</option>
                           <option value="18" {{ old('idcountry') == 18 ? 'selected' : '' }}>Finland</option>
                           <option value="19" {{ old('idcountry') == 19 ? 'selected' : '' }}>Romania</option>
                           <option value="20" {{ old('idcountry') == 20 ? 'selected' : '' }}>France</option>
                           <option value="21" {{ old('idcountry') == 21 ? 'selected' : '' }}>Slovakia</option>
                           <option value="22" {{ old('idcountry') == 22 ? 'selected' : '' }}>Germany</option>
                           <option value="23" {{ old('idcountry') == 23 ? 'selected' : '' }}>Slovenia</option>
                           <option value="24" {{ old('idcountry') == 24 ? 'selected' : '' }}>Greece</option>
                           <option value="25" {{ old('idcountry') == 25 ? 'selected' : '' }}>Spain</option>
                           <option value="26" {{ old('idcountry') == 26 ? 'selected' : '' }}>Hungary</option>
                           <option value="27" {{ old('idcountry') == 27 ? 'selected' : '' }}>Sweden</option>
                           <option value="28" {{ old('idcountry') == 28 ? 'selected' : '' }}>Ireland</option>
                           <option value="29" {{ old('idcountry') == 29 ? 'selected' : '' }}>United Kingdom</option>
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
      <div class="modal fade" id="myModalPassword" tabindex="-1" role="dialog" aria-labelledby="myModalPasswordLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="myModalPasswordLabel">Change Password</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form>
                     <div class="form-group">
                        <label for="exampleInputPassword1">Current password</label>
                        <input class="form-control" id="exampleInputPassword1" type="password" placeholder="Your password">
                     </div>
                     <div class="form-group">
                        <label for="exampleNewPassword">New Password</label>
                        <input class="form-control" id="exampleNewPassword" type="password" placeholder="New password">
                     </div>
                     <div class="form-group">
                        <label for="exampleConfirmPassword">Confirm Password</label>
                        <input class="form-control" id="exampleConfirmPassword" type="password" placeholder="Confirm password">
                     </div>
                     <button class="btn btn-primary btn-block mb-4">Change Password</button>
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
     @if(Auth::User()->id == $user->id)
     <div class="container-fluid bg-white">
        <div class="bg-white mb-0 mt-3 mb-3 panel">
           <h5><i class="fab fa-paypal"></i> Payments and Transfers</h5>
        </div>
        <p>BookHub requires you to have an IBAN associated to your account in order to bid and/or create auctions. Be mindful of these points:</p>
        <ul>
           <li>If you win an auction, you will be contacted by the seller in order to proceed with the payment. You pay within 5 days after receiving the payment instructions;</li>
           <li>You can neither create nor bid on auctions until you associate a valid IBAN to your BookHub account;</li>
           <li>You cannot unlink your IBAN if you have bid on an ongoing auction or if you have any auction of your own still active;</li>
           <li>Your shipping information must be valid and up to date. Neither we nor the seller will take responsibility if your shipment is lost due to inaccurate information.</li>
        </ul>
        <p><strong>{{$paypalMsg}}</strong></p>
        <div class="list-group" style = "margin-bottom: 30px;">
           <div class="container">
              <div class="row">
                 <div class="col-lg-12">
                    <a class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#paypalUpdateModal">
                    <i class="fa fa-link"></i> Associate an IBAN
                    </a>
                 </div>
              </div>
           </div>
        </div>
     </div>
     @endif
     <div class="modal fade" id="paypalUpdateModal" tabindex="-1" role="dialog" aria-labelledby="myModalPasswordLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
              <div class="modal-header">
                 <h5 class="modal-title" id="myModalPasswordLabel">Add an IBAN to your account</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
                 </button>
              </div>
              <div class="modal-body">
                 <form method="POST" action="{{ route('profile.paypal',$user->id) }}">
                    <div class="form-group">
                       <label for="exampleInputPassword1">Your new IBAN</label>
                       <input class="form-control" name="paypalEmail" type="email" placeholder="example@example.com" value = "{{$user->paypalEmail}}" required>
                       <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                    <button class="btn btn-primary btn-block mb-4">Add an IBAN to your account</button>
                 </form>
              </div>
           </div>
        </div>
     </div>
   </div>
</main>
