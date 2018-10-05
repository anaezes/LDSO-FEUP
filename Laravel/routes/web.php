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


// Create Auction
Route::get('create', 'CreateAuctionController@show')->name('create');
Route::post('create', 'CreateAuctionController@create');

// Auction Item Page
Route::get('auction/{id}', 'AuctionController@show')->name('auction');
Route::get('/auction', 'AuctionController@updateAuctions');
Route::get('auction/{id}/edit', 'AuctionController@edit')->name('auction.edit');
Route::post('auction/{id}/edit', 'AuctionController@submitEdit')->name('auction.edit');

// Profile Page
Route::get('profile/{id}', 'ProfileController@show')->name('profile');
Route::post('profile/{id}/edit', 'ProfileController@editUser')->name('profile.edit');
Route::post('/users/{id}/paypal', 'ProfileController@addPaypal')->name('profile.paypal');;
Route::delete('/users/{id}/paypal', 'ProfileController@removePaypal')->name('profile.paypal.remove');
Route::get('/users/{id}/comments', 'API\CommentController@getComments');
Route::post('/users/{id}', 'API\CommentController@postComment');

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
Route::post('api/wishlist','API\WishlistController@wish')->name('wish');

//Search Page
Route::get('search', 'SearchController@show')->name('search');
Route::post('search', 'SearchController@simpleSearch')->name('search');

//ListPages
Route::get('history', 'ListController@history')->name('history');
Route::get('myauctions', 'ListController@myauctions')->name('myauctions');
Route::get('auctions_im_in','ListController@auctions_imIn')->name('auctions_im_in');
Route::get('wishlist','ListController@wishlist')->name('wishlist');

//Moderator
Route::get('moderator','ModeratorController@show')->name('moderator');

//Administrator
Route::get('admin','AdministratorController@show')->name('admin');
