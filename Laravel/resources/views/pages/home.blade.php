@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="d-flex">
  <nav class="sidebar bg-dark hidden-p-md-up pb-2">
    <div class="text-white">
      <h5 class="pl-3 pt-3 font-weight-bold">Menu</h5>
      @if(Auth::check())
      <a class="text-white nav-item nav-link" href="#">Profile</a>
      @else
      <a class="text-white nav-item nav-link" href="#">Login</a>
      <a class="text-white nav-item nav-link" href="#">Register</a>
      @endif
      <a class="text-white nav-item nav-link" href="#">Repository</a>
      <a class="text-white nav-item nav-link" href={{ url("allproposals") }}>Proposals</a>
      <a class="text-white nav-item nav-link" href="#">People</a>
      <br>
      <h5 class="text-white pl-3 pb-2 font-weight-bold">Search</h5>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle hidden-p-md-down pl-3" href="#" id="catDropDown" data-toggle="dropdown" aria-expanded="false">
            All Faculties
          </a>
          <div class="dropdown-menu dropdown-menu-right" role="menu" id="catslist" aria-labelledby="catDropDown">
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Architecture
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Fine Arts
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Science
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Nutrition and Food Science
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Sports
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Law
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Economics
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Engineering
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Pharmacy
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Arts
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Medicine
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Dental Medicine
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Faculty of Psychology and Education Science
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Abel Salazar Institute of Biomedical Science
            </a>
            <a href="#" class="faculty-dropdown dropdown-item text-dark">
              Porto Business School
            </a>
          </div>
        </li>
      </ul>
      <div class="nav-item m-3">
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
      </div>
    </div>
  </nav>
  <div class="content p-4 mt-3">
    <section class="jumbotron text-center">
      <div class="container">
        <h1 class="jumbotron-heading"><b>Welcome to U.OPENLAB!</b></h1>
        <h4 class="text-dark hidden-p-md-down">Introduced in December of 2018, U.OPENLAB is a web platform that allows students from University of Porto to share their works as bids to proposals made by other users.</h4>
        @if (Auth::check())
        <span id="buttonsWelcome">
          <a href="{{ url('myproposals/')}}" class="btn btn-primary btn-lg my-2 mx-3 jumbotron-buttons">My Proposals</a>
          <a href="{{ url('proposals_im_in/')}}" class="btn btn-secondary btn-lg my-2 mx-3 jumbotron-buttons">Proposals I'm in</a>
        </span>
        @endif
      </div>
    </section>
    <!---  Active proposals grid -->
    <div id="proposalsAlbum" class="album py-2">
    </div>
    <a href="#" id="showmorebutton" class="btn btn-outline-primary my-2 btn-block">Show More</a>
  </div>
</div>

@endsection
