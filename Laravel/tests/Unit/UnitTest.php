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
     * Test history proposals route
     *
     * @return void
     */
    public function testRouteHistory()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $response = $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $response->followRedirects('faq');
        $this->assertTrue(Auth::check());

        $this->route('GET', 'history');
        $this->followRedirects('history');
        $this->assertResponseOk();
    }

    /**
     * Test history proposals route
     *
     * @return void
     */
    public function testRouteProposalsIWon()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $response = $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $response->followRedirects('faq');
        $this->assertTrue(Auth::check());

        $this->route('GET', 'proposalsIWon');
        $this->followRedirects('proposalsIWon');
        $this->assertResponseOk();
    }

    /**
     * Test my proposals route
     *
     * @return void
     */
    public function testRouteMyProposals()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $response = $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $response->followRedirects('faq');
        $this->assertTrue(Auth::check());

        $this->route('GET', 'myproposals');
        $this->followRedirects('myproposals');
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
    public function testRouteCreateProposalGet()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $this->be($user);
        $this->route('GET', 'create');
        $this->followRedirects('create');
        $this->assertResponseOk();
    }

    /**
     * Test create proposal post
     *
     * @return void
     */
    public function testRouteCreateProposalPost()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $proposal = factory(\App\Proposal::class)->make();
        $id = $proposal->id;

        $this->refreshApplication();

        $data = array(
            'title' => $proposal->title,
            'description' => $proposal->description,
            'skill' => array(1,2),
            'faculty' => array(1),
            'days' => 0,
            'hours' =>  1,
            'minutes' =>  3,
            'due' => $proposal->duedate,
            'announce' => $proposal->announcedate
        );

        $this->be($user);
        $response = $this->post(route('create'), $data)
            ->seeInDatabase('proposal', ['title' => $proposal->title]);
        $response->followRedirects('proposal'.$id);
        $response->assertResponseOk();
    }

    /**
     * Test route notify proposal
     *
     * @return void
     */
    public function testRouteProposalNotifyProponent()
    {

        //proposal/{id}/notify

        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $bid = factory(\App\Bid::class)->make();

        $data = array(
            'selfevaluation' => 4
        );

        $this->be($user);
        $response = $this->post(route('proposal.notify', ['id'=>$bid->idproposal]), $data);
        $response->followRedirects('proposal/'.$bid->idproposal);
    }

    /**
     * Test create bid get route
     *
     * @return void
     */
    public function testRouteCreateBidGet()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $response = $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $response->followRedirects('faq');
        $this->assertTrue(Auth::check());

        $bid = factory(\App\Bid::class)->create();

        $this->route('GET', 'createBid', ['id'=>$bid->id])
            ->isRedirect('createBid'.$bid->id);
    }

    /**
     * Test shou teams route
     *
     * @return void
     */
    public function testRouteTeamShow()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $team = factory(\App\Team::class)->create();

        $this->be($user);
        $this->route('GET', 'team.show', ['id'=> $team->id])
            ->isRedirect('team'.$team->id);
    }

    /**
     * Test team store route
     *
     * @return void
     */
    public function testRouteTeamStore()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $data = array(
            "teamName" => "nova equipa",
            "teamDescription" => "bla bla bla",
            "teamFaculty" => array(1,2)
        );

        $this->be($user);
        $this->post(route('team.store'), $data)
            ->seeInDatabase('team', ['teamname' => $data["teamName"]]);
    }

    /**
     * Test update team route
     *
     * @return void
     */
    public function testRouteTeamUpdate()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $team = factory(\App\Team::class)->create([
            'teamname' => "outro nome qualquer"
        ]);

        $this->get(route('team.update', ['id' => $team->id]), $team->toArray()); //fixme
          //  ->seeInDatabase('team', ['teamdescription' => "outro nome qualquer"]);
    }

    /**
     * Test add member route
     *
     * @return void
     */
    public function testRouteTeamAddMember1()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $user2 = factory(\App\User::class)->create();

        $team = factory(\App\Team::class)->create();

        $this->post(route('team.addMember', ['id' => $team->id]), $user2->toArray())
            ->seeInDatabase('team_member', ['idteam' => $team->id, 'iduser' => $user2->id]);
    }

    /**
     * Test add member to team route
     *
     * @return void
     */
    public function testRouteTeamAddMember2()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $user2 = factory(\App\User::class)->create([
            'username' => 12345
        ]);

        $team = factory(\App\Team::class)->create();

        $this->post(route('team.addMember', ['id' => $team->id]), $user2->toArray())
            ->assertResponseStatus(302);
    }

    /**
     * Test remove member team route
     *
     * @return void
     */
    public function testRouteTeamRemoveMember()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $user2 = factory(\App\User::class)->create();

        $team = factory(\App\Team::class)->create();

        $this->post(route('team.addMember', ['id' => $team->id]), $user2->toArray())
            ->seeInDatabase('team_member', ['idteam' => $team->id, 'iduser' => $user2->id]);

        $data = array(
            'memberId' => $user2->id,
            'source' => 'leader'
         );

        $response = $this->post(route('team.removeMember', ['id'=>$team->id]), $data);
        //$response->assertResponseOk(); fixme
    }

    /**
     * Test create bid post route
     *
     * @return void
     */
    public function testRouteCreateBidPost()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $proposal= factory(\App\Proposal::class)->create();
        $bid= factory(\App\Bid::class)->make();

        $this->refreshApplication();

        $data = array(
            'team' => $bid->idteam,
            'descriptionBid' => $bid->description,
            'submissionDate' => $bid->submissiondate
        );

        $this->be($user);
        $response = $this->post(route('createBid', ['id'=>$proposal->id]), $data)
            ->seeInDatabase('bid', ['description' => $bid->description]);
        $response->followRedirects('createBid'.$proposal->id);
    }

    /**
     * Test bid get route
     *
     * @return void
     */
    public function testRouteBidGet()
    {
        $bid = factory(\App\Bid::class)->create();
        $this->route('GET', 'bid', ['id' => $bid->id]);
        $this->followRedirects('bid'.$bid->id);
        $this->assertResponseOk();
    }

    /**
     * Test route bid put
     *
     * @return void
     */
    public function testRouteBidPut()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $proposal= factory(\App\Proposal::class)->create();
        $bid= factory(\App\Bid::class)->create();

        $data = array(
            'team' => $bid->idteam,
            'descriptionBid' => $bid->description,
            'submissionDate' => $bid->submissiondate
        );

        $this->be($user);
        $response = $this->post(route('createBid', ['id'=>$proposal->id]), $data)
            ->seeInDatabase('bid', ['description' => $bid->description]);
        $response->followRedirects('createBid'.$proposal->id);

        $data = array (
            'bidid' => $bid->id
        );

        $this->put(route('bid.winner', ['id'=>$bid->id]), $data);
        $this->seeInDatabase('bid', ['id'=> $bid->id, 'winner' => true]);
    }

    /**
     * Test proposal get route
     *
     * @return void
     */
    public function testRouteProposalGet()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->post(route('register'), $user->toArray())
            ->seeInDatabase('users', ['username' => $user->username]);
        $this->assertTrue(Auth::check());

        $proposal = factory(\App\Proposal::class)->create( );

        $this->route('GET', 'proposal', ['id'=>$proposal->id]);
        $this->followRedirects('proposal'.$proposal->id);
        $this->assertResponseOk();
    }

    /**
     * Test proposal edit get route
     *
     * @return void
     */
    public function testRouteProposalEditGet()
    {
        $proposal = factory(\App\Proposal::class)->create();

        $this->route('GET', 'proposal.edit', ['id' => $proposal->id])
            ->isRedirect('proposalEdit');
        $this->followRedirects('proposalEdit');
        $this->assertResponseOk();
    }

    /**
     * Test proposal update put route
     *
     * @return void
     */
    public function testRouteProposalUpdatePut()
    {
        $proposal = factory(\App\Proposal::class)->create([
            "proposal_status" => 'finished'
        ]);
        $response = $this->put(route('proposal.update', ['id' => $proposal->id]), $proposal->toArray());
        $this->seeInDatabase('proposal', ['description' => $proposal->description,
            'proposal_status' => 'finished']);
    }

    /**
     * Test profile get route
     *
     * @return void
     */
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
