@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="d-flex">
    <nav class="sidebar bg-dark hidden-p-md-up pb-4">
        <ul class="list-unstyled mt-4">
            <li>
                <h5 class="text-white pl-3 pb-2 ">Faculties</h5>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Architecture</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Fine Arts</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Science</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Nutrition and Food Science</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Sports</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Law</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Economics</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Engineering </a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Pharmacy</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Arts</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Medicine</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Dental Medicine</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Faculty of Psychology and Education Science</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Abel Salazar Institute of Biomedical Science</a>
            </li>
            <li>
                <a href="#" class="sidebar-toggle">
                    Porto Business School</a>
            </li>

        </ul>
    </nav>
    <div class="content p-4 mt-3">
        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading"><b>Welcome to U.OPENLAB!</b></h1>
                <h4 class="text-dark hidden-p-md-down">Introduced in December of 2018, U.OPENLAB is a web platform that allows students from University of Porto to share their works as bids to proposals made by other users.</h4>
                @if (Auth::check())
                <span id="buttonsWelcome">
                    <a href="{{ url('myproposals/')}}" class="btn btn-primary btn-lg my-2 mx-3 jumbotron-buttons">My proposals</a>
                    <a href="{{ url('proposals_im_in/')}}" class="btn btn-secondary btn-lg my-2 mx-3 jumbotron-buttons">proposals I'm in</a>
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
