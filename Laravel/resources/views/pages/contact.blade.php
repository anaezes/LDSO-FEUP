@extends('layouts.app')

@section('title', 'Contact')

@section('content')

<!--- Contact Content -->
        <div class="container-fluid bg-white">
            <main>
                <h1 class="my-4">Contact</h1>
                <div class="row">
                    <div class="col-md-6">
                        <p class="lead">Contact us through this form if you have any questions or issues regarding the platform</p>
                            <form id="contactForm" class="ml-4 mr-4" method="POST" action="{{ route('contact') }}" enctype="multipart/form-data">
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
                    <!-- Alert Notification Message-->
                    <div id="contactAlert">
                    </div>

                    </div>
                    <div class="col-md-6 mb-5 pb-3">
                        <p class="lead"> FEUP - Faculdade de Engenharia da Universidade do Porto</p>
                        <p> <i class="fas fa-map-marker-alt"></i> &nbsp; Rua Dr. Roberto Frias, 4200-465 Porto</p>
                        <p> <i class="fa fa-phone" aria-hidden="true"></i> &nbsp; +351 225-081-400 </p>
                        <p> <i class="fas fa-envelope"></i> <a href="mailto:helpdesk@fe.up.pt">&nbsp; helpdesk@fe.up.pt</a> </p>

                        <div> <iframe class="width100" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d96094.43758690929!2d-8.63081824172515!3d41.1792321282995!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x405225b4b451f7d7!2sFEUP+-+Faculdade+de+Engenharia+da+Universidade+do+Porto!5e0!3m2!1spt-PT!2spt!4v1519523514154"
                                height="270" style="border:0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

@endsection
