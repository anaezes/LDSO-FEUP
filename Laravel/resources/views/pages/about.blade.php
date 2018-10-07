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
                        <h2 class="my-4">U.OPENLAB</h2>
                        <p>The  U.Openlab  concept  is  developed  at  the  University  of  Porto  (U.Porto)  in  the  context  of  the teaching/learning  process  and  is  assumed  to  be  an  interaction  facilitator  platform  between  real-world needs  and  practical  works  topics.  The  aim  is  to  provide  innovative  answers  and  the  effective  use  of assessment  results,  the  credits  recognition  for  future  professional  or  scientific  careers  and  a sustainable process oriented for, among others, universities heritage services' needs.</p>
                        <p> The pilot-project in course at the University of Porto, focusing on the U.Porto Digital Museum, includes a  technological  infrastructure with  three  main  components:  several  collections,  an  information  and services  management  platform,  with  its  corresponding  digital  repository;  the  U.Porto  community contributions  management  platform  (U.Porto  OpenLab);  and  an  adaptive  framework  that  will  support various interface applications with non-specific audiences.</p>
                        <p> The   U.Porto   OpenLab   considers   two   main   areas:   the   bidding   management   area   /   calls   for contributions  and  a  contributions  personal  management  area.  The  professor,  the  student  and  the “client”  are  the  actors  in  the  process.  As  for  main  tasks  we  point  out  the  reception,  selection  and distribution  of  real  needs  /  problems.  Having  concluded  the  assessment  process,  results  were selected and an online publication was submitted. The inherent credits are recorded in a contributors' digital portfolio management and in the professional social networks profiles. </p>

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
                <div class="col-lg-4 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/Ana.jpg') }}" alt="Ana Santos photo">
                    <h4>Ana Santos<a href="https://github.com/anaezes" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/Arthur.jpg') }}" alt="Arthur photo">
                    <h4>Arthur Matta<a href="https://github.com/anaezes" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/Daniela.jpg') }}" alt="Daniela João photo">
                    <h4>Daniela João<a href="https://github.com/danjoao15" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
                <div class="col-lg-3 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/Francisco.jpg') }}" alt="Francisco Lopes photo">
                    <h4>Francisco Lopes<a href="https://github.com/ezspecial" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
                <div class="col-lg-3 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/Jose.jpg') }}" alt="Jose Azevedo photo">
                    <h4>José Azevedo<a href="https://github.com/zemafaz" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
                <div class="col-lg-3 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/nelson.jpg') }}" alt="Nelson Costa photo">
                    <h4>Nelson Costa<a href="https://github.com/mrnelsoncosta" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
                <div class="col-lg-3 col-sm-6 text-center mb-4">
                    <img class="rounded-circle img-fluid d-block mx-auto" src="{{ asset('img/Pedro.jpg') }}" alt="Pedro Ferreira photo">
                    <h4>Pedro Ferreira<a href="https://github.com/pmsferr" class="btn btn-social-icon btn-github"><i class="fab fa-github"></i></a></h4>
                    <p>Developer</p>
                </div>
            </div>
        </div>
    </div>

@endsection
