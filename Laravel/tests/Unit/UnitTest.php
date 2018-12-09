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
        $this->assertResponseOk();
    }

    /**
     * Test resgister user route
     *
     * @return void
     */
    public function testRouteRegisterUser()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $response = $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $response->followRedirects('faq');
        $this->assertTrue(Auth::check());
    }

    /**
     * Test logout user route
     *
     * @return void
     */
    public function testRouteLogout()
    {
        //register
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray());

        $response = $this->get(route('logout'));
        $response->dontSee($user->username);
        $response->followRedirects('home');
    }

    /**
     * Test login user route
     *
     * @return void
     */

    public function testRouteLogin()
    {
        //register
        $user = factory(\App\User::class)->create();
        $this->post(route('register'), $user->toArray());

        //logout
        $this->get(route('logout'));

        $response = $this->post(route('login'), $user->toArray());
        $this->be($user);
        $response->see($user->username, true);
        $this->assertTrue(Auth::check());
        $response->followRedirects('home');
    }

    /**
     * Test login user route
     *
     * @return void
     */

    public function testAddProposal()
    {
        //register
        $user = factory(\App\User::class)->create();
        $this->post(route('register'), $user->toArray());

       $proposal = factory(\App\Proposal::class)->create([
            'idproponent' => $user->id
        ]);

       /*
       $timestamp1 = mt_rand(1, time());
       $timestamp2 = mt_rand(1, time()+$timestamp1);

       $data[] = array(
            'title' => 'blablabla',
            'duration' => array(
                'days' => rand(0,14),
                'hours' => rand(0,23),
                'minuts' =>  rand(0,59),
            ),
            'announce' =>  date("d M Y", $timestamp1),
            'description' => 'blablabla',
            'due' => date("d M Y", $timestamp2),
            'public_prop' => 'on',
            'public_bid' => 'on',
            'faculty' => array(1,2),
            'skill' => array(1,2)
        );
        */


        $response = $this->post(route('create'), $proposal->toArray())
            ->assertRedirectedTo('home') //fixme
            ->seeInDatabase('proposal', ['title' => $proposal->title])
            ->seeInDatabase('proposal', ['idproponent' => $user->id]);

     //   $response->assertRedirectedTo('');
/*

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


    }
}
