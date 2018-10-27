<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('home');
});

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
// Auth Reset Password
Route::post('password/email', 'Auth\ForgotPasswordControllerCustom@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.doreset');

// Home
Route::get('home', 'HomeController@show')->name('home');


// Create Proposal
Route::get('create', 'CreateProposalController@show')->name('create');
Route::post('create', 'CreateProposalController@create');

// Create Team

Route::post('createteam', 'CreateTeamController@create');
Route::get('createteam', 'CreateTeamController@show')->name('create_team');

// Proposal Item Page
Route::get('proposal/{id}', 'ProposalController@show')->name('proposal');
Route::get('/proposal', 'ProposalController@updateProposals');
Route::get('proposal/{id}/edit', 'ProposalController@edit')->name('proposal.edit');
Route::post('proposal/{id}/edit', 'ProposalController@submitEdit')->name('proposal.edit');

// Profile Page
Route::get('profile/{id}', 'ProfileController@show')->name('profile');
Route::post('profile/{id}/edit', 'ProfileController@editUser')->name('profile.edit');

//Contact
Route::get('contact', 'ContactController@show')->name('contact');
Route::post('contact', 'ContactController@message');

//FAQ
Route::get('faq', 'FaqController@show')->name('faq');

//About
Route::get('about', 'AboutController@show')->name('about');

//API
Route::get('api/search', 'API\SearchController@search');
Route::get('api/bid', 'API\BidController@getMaxBid');
Route::post('api/bid', 'API\BidController@bidNewValue');
Route::get('api/notifications', 'API\NotificationsController@getNotifications');
Route::post('/notifications/{id}','API\NotificationsController@markAsSeen');
Route::post('api/admin','API\AdminController@action')->name('admin');
Route::post('api/moderator','API\ModeratorController@action')->name('moderator');

//Search Page
Route::get('search', 'SearchController@show')->name('search');
Route::post('search', 'SearchController@simpleSearch')->name('search');

//ListPages
Route::get('history', 'ListController@history')->name('history');
Route::get('teams', 'ListController@teams')->name('teams');
Route::get('myproposals', 'ListController@myproposals')->name('myproposals');
Route::get('proposals_im_in','ListController@proposals_imIn')->name('proposals_im_in');
Route::get('allproposals', 'ListController@allproposals')->name('allproposals');

//Moderator
Route::get('moderator','ModeratorController@show')->name('moderator');

//Administrator
Route::get('admin','AdministratorController@show')->name('admin');
