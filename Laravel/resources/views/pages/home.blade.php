@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="d-flex">
    <nav class="sidebar bg-dark hidden-p-md-up pb-4">
        <ul class="list-unstyled mt-4">
            <li>
                <h5 class="text-white pl-3 pb-2 ">Categories</h5>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Arts&amp;Music</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Biographies</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Business</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Kids</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Comics</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Cooking</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Computation&amp;Tech</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Education</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Health&amp;Fitness</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    History</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Horror</a>
            </li>
            <li>
                <a href="#submenu1" data-toggle="collapse">
                    Literature</a>
                <ul id="submenu1" class="list-unstyled collapse">
                    <li>
                        <a href="#" class="sidebar-toggle">All</a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-toggle">Anthologies</a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-toggle">Classics</a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-toggle">Contemporary</a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-toggle">Sci-Fi&amp;Fantasy</a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-toggle">Romance</a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-toggle">Crime</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Religion</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Science</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Self-Help</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Travel</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Other</a>
            </li>
        </ul>
    </nav>
    <div class="content p-4 mt-3">
        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading"><b>Welcome to Bookhub!</b></h1>
                <h4 class="text-dark hidden-p-md-down">Introduced in May of 2018, BookHub is a web platform that allows EU residents to participate in online book auctions.</h4>
                @if (Auth::check())
                <span id="buttonsWelcome">
                    <a href="{{ url('myauctions/')}}" class="btn btn-primary btn-lg my-2 mx-3 jumbotron-buttons">My Auctions</a>
                    <a href="{{ url('auctions_im_in/')}}" class="btn btn-secondary btn-lg my-2 mx-3 jumbotron-buttons">Auctions I'm in</a>
                </span>
                @endif
            </div>
        </section>
        <!---  Active auctions grid -->
        <div id="auctionsAlbum" class="album py-2">
        </div>
        <a href="#" id="showmorebutton" class="btn btn-outline-primary my-2 btn-block">Show More</a>
    </div>
</div>

@endsection
