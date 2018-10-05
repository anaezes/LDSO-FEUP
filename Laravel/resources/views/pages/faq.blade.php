@extends('layouts.app')

@section('title', 'FAQ')

@section('content')

<!--- FAQ Content -->
        <div class="container p-5">
            <main>
                <h1 class="my-4 text-center">FAQ - Frequently Asked Questions</h1>
                <!-- FAQ block start -->
                <div class="container py-5">
                    <div class="panel-group" id="faqAccordion">

                        <!-- Question 1 -->
                        <div class="panel panel-default py-2">
                            <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question0">
                                <h4 class="panel-title">
                                    <a href="#question0" class="ing">What is BookHub?</a>
                                </h4>
                            </div>

                            <div id="question0" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">

                                    <p>BookHub is a web platform that allows EU residents to register and pariticipate in online book auctions. The auctions are limited to literary items and can last from 5 minutes to 7 days. The payment is processed through
                                        Paypal.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Question 2 -->
                        <div class="panel panel-default py-2">
                            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question1">
                                <h4 class="panel-title">
                                    <a href="#question1" class="ing">What should I know before I start participating in book auctions?</a>
                                </h4>
                            </div>

                            <div id="question1" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">

                                    <p>After registration, you can participate in book auctions as a buyer and as a seller. You must follow the rules concerning each of these roles described in this FAQ or you may subjected to a ban by the moderating team.
                                        Only one registred account is allowed per person. </p>
                                </div>
                            </div>
                        </div>


                        <!-- Question 3 -->
                        <div class="panel panel-default py-2">
                            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question2">
                                <h4 class="panel-title">
                                    <a href="#question2" class="ing">What should I do if I want to participate in an auction as a buyer?</a>
                                </h4>
                            </div>

                            <div id="question2" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">

                                    <p>As a buyer you are allowed to bid in other user's auctions. If you win the auction, you are charged immediately through your associated PayPal account. Any misbehavior regarding PayPal may result in a permanent ban.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Question 4 -->
                        <div class="panel panel-default py-2">
                            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question3">
                                <h4 class="panel-title">
                                    <a href="#question3" class="ing">What should I do if I want to create an auction and participate as a seller?</a>
                                </h4>
                            </div>

                            <div id="question3" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">

                                    <p>As a seller you are allowed to create new auctions and wait for other users biddings until the auction ends. You are not allowed to bid your own auctions. A new auction will only be made public after it's been aproved
                                        by the moderator team. After the auction closes, you should ship the item using a method that provides tracking information. All shipping costs will have to be covered by you, and you must ensure that the item reaches
                                        the buyer in 40 or less days. You will receive the buyer's shipping address through a notification. You should receive the payment to your Paypal account within 48h. The buyer is allowed to leave a feedback in your
                                        profile regarding his experience.</p>
                                </div>
                            </div>
                        </div>


                        <!-- Question 5 -->
                        <div class="panel panel-default py-2">
                            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question4">
                                <h4 class="panel-title">
                                    <a href="#question4" class="ing">What should I do if I bought a book but it never came?</a>
                                </h4>
                            </div>

                            <div id="question4" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">

                                    <p> You must give 40 full days after the shipping of the book. After that time, if you still haven't receive the book, <a href="contact.html">message</a> the moderating team about your issue and you will be contacted within
                                        48 hours through your specified e-mail address. Alternatively, contact the seller directly.</p>
                                </div>
                            </div>
                        </div>


                        <!-- Question 6 -->
                        <div class="panel panel-default py-2">
                            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question5">
                                <h4 class="panel-title">
                                    <a href="#question5" class="ing">The auction of my book has ended but the winner didn't pay, what should I do?</a>
                                </h4>
                            </div>

                            <div id="question5" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">

                                    <p>You should give 48h after the auction is over. Payments are done via PayPal and are automatic, but it is not guaranteed that the transaction will be successfully finished. If after 48h the you haven't yet received your
                                        payment, message us, and we will find out the reason. If it happens to be the work of a fraudulent user and/or PayPal account, you will be allowed to create a new auction for the same item. </p>
                                </div>
                            </div>
                        </div>


                        <!-- Question 7 -->
                        <div class="panel panel-default py-2">
                            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question6">
                                <h4 class="panel-title">
                                    <a href="#question6" class="ing">What information should I leave as a feedback?</a>
                                </h4>
                            </div>

                            <div id="question6" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">

                                    <p>As a buyer, after a purchase, you are expected to express your apreciation or depreciation related to your purchase experience. After the positive or negative feedback choice you can leave a customized message that
                                        can detail the experience related to the shipping, packaging, resemblence of the item to the decription and communication with the seller. </p>
                                </div>
                            </div>
                        </div>


                        <!-- Question 8 -->
                        <div class="panel panel-default py-2">
                            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question7">
                                <h4 class="panel-title">
                                    <a href="#question7" class="ing">How do I contact the seller if I want more information about an item?</a>
                                </h4>
                            </div>

                            <div id="question7" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">

                                    <p>BookHub provides its users with a messaging system. Alternatively, you can contact users through their email address, which is shown on their profiles.</p>
                                </div>
                            </div>
                        </div>


                        <!-- Question 9 -->
                        <div class="panel panel-default py-2">
                            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question8">
                                <h4 class="panel-title">
                                    <a href="#question8" class="ing">I created an auction but made some mistakes, can I edit the auction after it's already active?</a>
                                </h4>
                            </div>

                            <div id="question8" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">

                                    <p>Editing auctions should be avoided, but it is possible to do so. Every modification must be additive rather than destructive (eg. you can only add more things, such as extra images), and it must be approved by the moderator
                                        team before it updates. Repeated erroneous use of this feature may end up in a ban.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Question 10 -->
                        <div class="panel panel-default py-2">
                                <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question10">
                                    <h4 class="panel-title">
                                        <a href="#question10" class="ing">Where can i see the auctions i'm related to?</a>
                                    </h4>
                                </div>
    
                                <div id="question10" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
                                        
                                        <p>If you're logged in, in the upper right corner there's your username. Click there and choose beetween all the options we have at your disposal.  
                                        </p>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- FAQ block end -->
            </main>
        </div>
    </div>

@endsection