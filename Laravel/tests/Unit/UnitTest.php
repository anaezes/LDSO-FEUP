<?php

namespace Tests\Unit;

use App\Team;
use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use database\factories;

class UnitTest extends TestCase
{
    /**
     * Test homepage route
     *
     * @return void
     */
    public function testRouteHome()
    {
        $this->route('GET', 'home');
        $this->followRedirects('home');
        $this->assertResponseOk();
    }

    /**
     * Test all proposals route
     *
     * @return void
     */
    public function testRouteAllProposals()
    {
        $this->route('GET', 'allproposals');
        $this->followRedirects('allproposals');
        $this->assertResponseOk();
    }

    /**
     * Test resgister user route
     *
     * @return void
     */
    public function testRouteRegisterUserPost()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $response = $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $response->followRedirects('faq');
        $this->assertTrue(Auth::check());
    }

    /**
     * Test resgister user route
     *
     * @return void
     */
    public function testRouteRegisterUserGet()
    {
        $this->route('GET', 'register');
        $this->followRedirects('register');
        $this->assertResponseOk();
    }

    /**
     * Test logout user route
     *
     * @return void
     */
    public function testRouteLogoutGet()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $response = $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $response->followRedirects('faq');
        $this->assertTrue(Auth::check());


        $this->route('GET', 'logout');
        $this->followRedirects('logout');
        $this->assertFalse(Auth::check());
    }

    /**
     * Test login user route
     *
     * @return void
     */

    public function testRouteLoginPost()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $response = $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $response->followRedirects('faq');
        $this->assertTrue(Auth::check());

        $this->route('GET', 'logout');
        $this->assertFalse(Auth::check());

        $response = $this->post(route('login'), $user->toArray());
        $this->be($user);
        $this->assertTrue(Auth::check());
        $response->followRedirects('home');
    }

    /**
     * Test login user route
     *
     * @return void
     */

    public function testRouteLoginGet()
    {
        $this->route('GET', 'login');
        $this->followRedirects('login');
        $this->assertResponseOk();
    }

    /**
     * Test login user route
     *
     * @return void
     */

    public function testAddProposalPost()
    {

        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $proposal = factory(\App\Proposal::class)->create();
        $this->be($user);
        $response = $this->post(route('create'), $proposal->toArray()) //fixme
            ->seeInDatabase('proposal', ['title' => $proposal->title]);
        $response->followRedirects('create');
    }

    public function testRouteCreateProposalGet()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $this->route('GET', 'create');
        $this->followRedirects('create');
        $this->assertResponseOk();
    }

    public function testRouteCreateBidGet()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $response = $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $response->followRedirects('faq');
        $this->assertTrue(Auth::check());

        $bid = factory(\App\Bid::class)->create();

        $this->route('GET', 'createBid', [$bid->id]);
        $this->followRedirects('createBid'.$bid->id);
        $this->assertResponseOk();
    }

    public function testRouteCreateBidPost()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $response = $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $response->followRedirects('faq');
        $this->assertTrue(Auth::check());

        $bid = factory(\App\Bid::class)->create();

        //$this->route('POST', 'createBid', [$bid->idproposal, $bid->toArray()]); fixme ?
        //$this->seeInDatabase('bid', ['description' => $bid->description]);
    }


    public function testRouteBidPut()
    {
        $bid = factory(\App\Bid::class)->create([
            "winner" => true
        ]);
        $this->route('PUT', 'bid.winner', ['id' => $bid->id]);
        $this->seeInDatabase('bid', ['description' => $bid->description, 'winner' => 'true']);
    }

    public function testRouteProposalGet()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $proposal = factory(\App\Proposal::class)->create();

        $this->route('GET', 'proposal', [$proposal->id]);
        $this->followRedirects('proposal'.$proposal->id);
        $this->assertResponseOk();
    }

    public function testRouteProposalEditGet()
    {
        //Route::get('/proposal', 'ProposalController@updateProposals');
        $proposal = factory(\App\Proposal::class)->create([
            "proposal_status" => 'finished'
        ]);

        $this->route('GET', 'proposal.edit',  ['id' => $proposal->id]);
        $this->followRedirects('proposal'.$proposal->id);
        $this->assertResponseOk();
    }


    public function testRouteProposalUpdatePut()
    {
        //Route::put('proposal/{id}', 'ProposalController@update')->name('proposal.update');
        $proposal = factory(\App\Proposal::class)->create([
            "proposal_status" => 'finished'
        ]);
        $this->route('PUT', 'proposal.update', ['id' => $proposal->id]);
        $this->seeInDatabase('proposal', ['description' => $proposal->description, 'proposal_status' => 'finished']);
    }


    public function testRouteProfileGet()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $this->route('GET', 'profile',  [$user->id]);
        $this->followRedirects('profile'.$user->id);
        $this->assertResponseOk();
    }

   public function testRouteProfilePost()
    {
        //Route::post('profile/{id}/edit', 'ProfileController@editUser')->name('profile.edit');
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $response = $this->post(route('profile.edit', $user->id), $user->toArray());
        //$this->assertResponseOk(); fixme
    }

    public function testRouteContactGet()
    {
        $this->route('GET', 'contact');
        $this->followRedirects('contact');
        $this->assertResponseOk();
    }

    public function testRouteAboutGet()
    {
        $this->route('GET', 'about');
        $this->followRedirects('about');
        $this->assertResponseOk();
    }
}
