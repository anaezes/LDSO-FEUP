@extends('layouts.app')

@section('title', 'About')

@section('content')

<!--- About Content -->
       <div class="container p-5 align-self-center">
            <h1 class="my-4">About</h1>

            <!-- About BookHub Row -->
            <section class="py-2">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="my-4">BookHub</h2>
                        <p>Introduced in May of 2018, BookHub is a web platform that allows EU residents to pariticipate in online book auctions.</p>
                        <p> BookHub restricts it's users to an EU residency to simplify the custom and shipping formalities. Registred users are allowed to add their own items to the auction list or bid other user's auctions.</p>
                        <p> The payment is processed via Paypal at the end of each auction and every item is regulated by a moderating team to assure the best experience between the users. </p>
                        <p> Adress the FAQ list for more information about the bidding and purchase procedure.</p>
                        <p> Any further informations or issues should be reported to the moderators using the contact page. </p>
                    </div>
                    <div class="col-md-6 my-4">
                        <img class=img-fluid src="{{ asset('img/bookhub_about.jpeg') }}" alt="library">
                    </div>
                </div>
            </section>

            <!-- Team Members Row -->
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="my-3">The Team</h2>
                </div>
                <div class="col-lg-3 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/daniel.jpg') }}" alt="Daniel Azevedo photo">
                    <h4>Daniel Azevedo<a href="https://github.com/3rdvision" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
                <div class="col-lg-3 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/nelson.jpg') }}" alt="Nelson Costa photo">
                    <h4>Nelson Costa<a href="https://github.com/mrnelsoncosta" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
                <div class="col-lg-3 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/ruben.jpg') }}" alt="Ruben Torres photo">
                    <h4>Rúben Torres<a href="https://github.com/rjstorres" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
                <div class="col-lg-3 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/tiago.jpg') }}" alt="Tiago Santos photo">
                    <h4>Tiago Santos<a href="https://github.com/tiagolascasas" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
            </div>
        </div>
    </div>

@endsection
