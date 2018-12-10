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
                        <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question1">
                            <h4 class="panel-title">
                                <a href="#question1" class="ing">1 - What is U.Openlab?</a>
                            </h4>
                        </div>
                        <div id="question1" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    The U.Openlab concept is developed at the University of Porto (U.Porto) in the context of the teaching/learning process and is assumed to be an interaction facilitator platform between real-world needs and practical works topics. The aim is to provide innovative answers and the effective use of assessment results, the credits recognition for future professional or scientific careers and a sustainable process oriented for, among others, universities heritage services' needs.
                                    <br>
                                    The pilot-project in course at the University of Porto, focusing on the U.Porto Digital Museum, includes a technological infrastructure with three main components: several collections, an information and services management platform, with its corresponding digital repository; the U.Porto community contributions management platform (U.Porto OpenLab); and an adaptive framework that will support various interface applications with non-specific audiences.
                                    <br>
                                    The U.Porto OpenLab considers two main areas: the bidding management area / calls for contributions and a  contributions personal management area. The professor, the student and the “client” are the actors in the process. As for main tasks we point out the reception, selection and distribution of real needs / problems. Having concluded the assessment process, results were selected and an online publication was submitted. The inherent credits are recorded in a contributors' digital portfolio management and in the professional social networks profiles. The methodology will be presented and discussed as well as the process along with the obstacles and other factors contributing to the success and future replication of this experience.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 2 -->
                    <div class="panel panel-default py-2">
                        <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question2">
                            <h4 class="panel-title">
                                <a href="#question2" class="ing">2 - What should I know before I create a proposal or a bid?</a>
                            </h4>
                        </div>
                        <div id="question2" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    Before creating a proposal, make sure that you have all the information you need well organized. Incomprehensible proposals will not be approved, as well as fraudulent-like proposals.
                                    <br>
                                    Before creating a bid, you must create or join a team. Notice that after creating a team, you can delete or edit it, but after bidding on a proposal, you will not be able to delete the team anymore.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 3 -->
                    <div class="panel panel-default py-2">
                        <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question3">
                            <h4 class="panel-title">
                                <a href="#question3" class="ing">3 - How can I create a proposal?</a>
                            </h4>
                        </div>
                        <div id="question3" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    To create a proposal, you must first sign in to your account. Then click on '<i class="fas fa-plus"></i>' button or go to 'Create auction' located in the menu under your username, both located on the top right corner of the window.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 4 -->
                    <div class="panel panel-default py-2">
                        <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question4">
                            <h4 class="panel-title">
                                <a href="#question4" class="ing">4 - How can I create a team?</a>
                            </h4>
                        </div>
                        <div id="question4" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    To create a team, you must first sign in to your account. Then click on your username on the top right corner of the window and go to 'My Teams'. Click on '<i class="fas fa-plus"></i> Create Team' link.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 5 -->
                    <div class="panel panel-default py-2">
                        <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question5">
                            <h4 class="panel-title">
                                <a href="#question5" class="ing">5 - How can I join a team?</a>
                            </h4>
                        </div>
                        <div id="question5" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    To join a team, you must first sign up. Then, contact the team leader providing your username and asking him/her to invite you.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 6 -->
                    <div class="panel panel-default py-2">
                        <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question6">
                            <h4 class="panel-title">
                                <a href="#question6" class="ing">6 - How can I make a bid to a proposal?</a>
                            </h4>
                        </div>
                        <div id="question6" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    To make a bid to a proposal, you must first be in a team. Then, go to the page of the proposal you want to bid and click '<i class="fas fa-plus"></i> Bid'.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 7 -->
                    <div class="panel panel-default py-2">
                        <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question7">
                            <h4 class="panel-title">
                                <a href="#question7" class="ing">7 - Why I can not see the bids in a proposal?</a>
                            </h4>
                        </div>
                        <div id="question7" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    Some proposals have their bids section set to private, i.e., only the proponent can see them.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Question 8 -->
                    <div class="panel panel-default py-2">
                        <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question8">
                            <h4 class="panel-title">
                                <a href="#question8" class="ing">8 - What does <i>'The proposal has finished'</i> means?</a>
                            </h4>
                        </div>
                        <div id="question8" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    A proposal is set to finished either when a team submit its project or when the proposal expires, i.e., the current date is higher than the proposal's due date.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 9 -->
                    <div class="panel panel-default py-2">
                        <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question9">
                            <h4 class="panel-title">
                                <a href="#question9" class="ing">9 - Why I can not see some proposals?</a>
                            </h4>
                        </div>
                        <div id="question9" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    The proposals a user can see are those which are public or that is associated with the faculty of the user. If you can not see a proposal, it probably means that it is not public, it is not associated to your faculty, or it has finished. 
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 10 -->
                    <div class="panel panel-default py-2">
                        <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question10">
                            <h4 class="panel-title">
                                <a href="#question10" class="ing">10 - Where can i see the proposals I'm related to?</a>
                            </h4>
                        </div>
                        <div id="question10" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    To see the proposals you are related to you must first sign in to your account. Then you can see the proposals you bid in by clicking on your username in the top right corner of the window and selecting "Proposals I'm in". You can also see the proposals you won by clicking instead in "Proposals I won".
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Question 11 -->
                    <div class="panel panel-default py-2">
                        <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question11">
                            <h4 class="panel-title">
                                <a href="#question11" class="ing">11 - Where can i see the proposals I created?</a>
                            </h4>
                        </div>
                        <div id="question11" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <p class="text-justify">
                                    To see the proposals you created you must first sign in to your account. Then, in the homepage, click 'My proposals' button, or click on your username in the top right corner of the window and select 'My proposals'. 
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