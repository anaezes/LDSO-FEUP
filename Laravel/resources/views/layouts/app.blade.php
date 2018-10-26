<!DOCTYPE html>
<html lang="en">

<head>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel')}} - @yield('title') </title>

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta id="csrfToken" name="csrf-token" content="{{ csrf_token() }}">

  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bsadmin.css') }}" rel="stylesheet">
  @if (Auth::check())
  <script> let user_id = "{{ Auth::user()->id }}";</script>
  @endif

</head>

<body>


  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a href="#" class="sidebar-toggle hidden-p-md-up pb-1 text-light mr-3 navbar-brand">
      <i class="fa fa-bars"></i>
    </a>
    <a class="navbar-brand" href="{{ url('home/') }}">U.OPENLAB</a>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-item nav-link" href="#">Repository</a>
        <a class="nav-item nav-link" href={{ url("allproposals") }}>Proposals</a>
        <a class="nav-item nav-link" href="#">People</a>
      </div>
      <ul class="navbar-nav ml-auto" id="navbarList">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle hidden-p-md-down" href="#" id="catDropDown" data-toggle="dropdown" aria-expanded="false">
            All Faculties
          </a>
          <div class="dropdown-menu dropdown-menu-right" role="menu" id="catslist" aria-labelledby="catDropDown">

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Architecture
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Fine Arts
            </a>


            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Science
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Nutrition and Food Science
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Sports
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Law
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Economics
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Engineering
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Pharmacy
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Arts
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Medicine
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Dental Medicine
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Faculty of Psychology and Education Science
            </a>

            <a href="#" class="faculty-dropdown dropdown-item">
              Abel Salazar Institute of Biomedical Science
            </a>


            <a href="#" class="faculty-dropdown dropdown-item">
              Porto Business School
            </a>

          </li>
          <li class="nav-item hidden-p-md-down">
            <form class="form-inline my-2 my-lg-0 mr-lg-2 searchNav" method="POST" action="{{ route('search') }}">
              <div class="input-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="faculty" value="All">
                <input name="searchTerm" class="form-control" type="text" placeholder="Search for...">
                <span class="input-group-append">
                  <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </span>
              </div>
            </form>
          </li>

          <li class="nav-item">
            <a class="nav-link hidden-p-md-up" href="#" id="navbarDropDownSearch" data-toggle="dropdown">
              <i class="fa fa-search"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-label="navbarDropdownSearch">
              <form class="form-inline searchNav" method="POST" action="{{ route('search') }}">
                <div class="input-group">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="faculty" value="All">
                  <input name="searchTerm" class="form-control" type="text" placeholder="Search for...">
                  <span class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                      <i class="fa fa-search"></i>
                    </button>
                  </span>
                </div>
              </form>
            </div>

          </li>
          @if (Auth::check())
          <a class="nav-link" id="create_proposal" href="{{ url("create/") }}" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-plus" title="Add a new Proposal"></i>
          </a>
          <li class="nav-item dropdown"  id = "dropdown_noti">
            <a class="nav-link dropdown-toggle container" id="alertsDropdown" href="#" data-toggle="dropdown"  onclick="notificationsClick()" aria-haspopup="true" aria-expanded="false">
              <i class="label label-pill label-light count" id = "counter" style="border-radius:10px;"></i>
              <span class="badge badge-danger"></span>
              <i class="fa fa-fw fa-bell"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right notifications" id = "not_itens" aria-labelledby="alertsDropdown">
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle  hidden-p-md-down" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown">
              <i class="fa fa-user"></i> {{Auth::user()->username}}
            </a>
            <a class="nav-link dropdown-toggle  hidden-p-md-up" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown">
              <i class="fa fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink1 navbarDropdownMenuLink2">
              <a class="dropdown-item" href="{{ url("profile/") }}/{{Auth::user()->id}}">Profile</a>
              @if(Auth::user()->users_status=="moderator")
              <a class="dropdown-item" href="{{ url("moderator") }}">Moderator Panel</a>
              @endif
              @if(Auth::user()->users_status=="admin")
              <a class="dropdown-item" href="{{ url("admin") }}">Admin Panel</a>
              @endif
              <a class="dropdown-item" href="{{ url("create/") }}">Create Proposal</a>
              <a class="dropdown-item" href="{{ url("myproposals") }}">My Proposals</a>
              <a class="dropdown-item" href="{{ url("proposals_im_in") }}">Proposals I'm in</a>
              <a class="dropdown-item" href="{{ url("history") }}">History</a>
              <a class="dropdown-item" href="{{ url("wishlist") }}">WishList</a>
              <a  class="dropdown-item" href="messages.html">Messages</a>
              <a class="dropdown-item" href="{{ url("logout") }}">Logout</a>
            </div>
          </li>
          @else
          <li class="nav-item ">
            <a class="nav-link hidden-p-md-down" data-toggle="modal" href="#" data-target="#myModalLogin">
              <i class="fa fa-sign-in-alt"></i> Login
            </a>
            <a class="nav-link hidden-p-md-up" data-toggle="modal" href="#" data-target="#myModalLogin">
              <i class="fa fa-sign-in-alt"></i>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link hidden-p-md-down" data-toggle="modal" href="#" data-target="#myModalRegister">
              <i class="fa fa-user-plus"></i> Register
            </a>
            <a class="nav-link hidden-p-md-up" data-toggle="modal" href="#" data-target="#myModalRegister">
              <i class="fa fa-user-plus"></i>
            </a>
          </li>
          @endif
        </ul>
    </div>
  </nav>

    <div class="modal fade" id="myModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLoginLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLoginLabel">Login</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @include('auth.login')
        </div>
      </div>
    </div>

    <div class="modal fade" id="myModalRegister" tabindex="-1" role="dialog" aria-labelledby="myModalRegisterLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalRegisterLabel">Register</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @include('auth.register')
        </div>
      </div>
    </div>

    <div class="modal fade" id="myModalForgotPassword" tabindex="-1" role="dialog" aria-labelledby="myModalForgotPasswordLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalForgotPasswordLabel">Retrieve Your Password</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @include('auth.forgot_password')
        </div>
      </div>
    </div>




    @include('partials.errors')
    @yield('content')



    <!-- Footer -->
    <footer class="footer footer-offset py-2 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; U.OPENLAB 2018</p>
        <p class="m-0 text-center text-white">
          <a class="text-white" href="{{ url('about/') }}"> About</a> &nbsp; | &nbsp;
          <!-- <a class="text-white" href="{{ url('faq/') }}"> FAQ</a> &nbsp; | &nbsp;-->
          <a class="text-white" href="{{ url('contact/') }}"> Contact</a>
        </p>
      </div>
    </footer>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/easytimer.min.js') }}"></script>
    <script src="{{ asset('js/bsadmin.js') }}"></script>
  </body>

  </html>
