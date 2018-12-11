<?php

namespace Tests\acceptance;

use Tests\TestCase;
use database\factories;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Database\Eloquent\Model;


class AcceptanceTests extends TestCase
{


   protected $user;


    /**
     * Test Homepage.
     * User-Homepage
     * @return void
     */
    public function testHomepage()
    {
        $this->visit('/home')
             ->see('Welcome to U.OPENLAB!')
             ->dontSee('Rails');
    }

    /**
     * Test for register user.
     *  Visitor-Register
     * @return void

     */
    public function testNewUserRegistration()
    {


            $this->visit('/home')
                ->click('Register')
                ->type('Teste', 'name')
                ->type('teste', 'username')
                ->type('teste@fe.up.pt', 'email')
                ->select('2','idfaculty')
                ->type('919191919', 'phone')
                ->type('123456', 'password')
                ->type('123456', 'password_confirmation')
                ->press('REGISTER')
                ->click('navbarDropdownMenuLink2')
                ->click('Profile')
                ->seePageIs('/profile/1');


    }


    /**
     * Test for login user.
     *  Visitor-Authentication
     * @return void
     */

    public function testLoginUser()
    {

        $user = factory(\App\User::class)->create();


          $this->actingAs($user)
              ->visit('/home')
              ->type($user->username, 'username')
              ->type($user->password, 'password')
              ->press('LOGIN')
              ->click('navbarDropdownMenuLink2')
              ->click('Profile')
              ->seePageIs('/profile/'.$user->id);



    }

    /**
     * Test for logout.
     *  Member-Logout
     * @return void
     */

    public function testLogoutUser()
    {

        $user = factory(\App\User::class)->create();

        $this->actingAs($user)
            ->visit('/home')
            ->click('Logout')
            ->seePageIs('/home');

    }


    /**
     * Test for access profile page.
     *  Member-Profile page
     * @return void
     */
    public function testAccessProfile()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('Profile')
            ->seePageIs('/profile/'.$user->id);

    }


    /**
     * Test for edit profile page.
     *
     * @return void
    */

    public function testEditProfile()
    {   $user = factory(\App\User::class)->create();

        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('Profile')
            ->click('Edit Info')
            ->type('Teste1', 'name')
            ->press('Save any new changes')
            ->assertEquals("Teste1", $user->username);


    }



    /**
     * Test for New proposal page
     * User-Auctions page
     * @return void
     */

    public function testProposalPage()
    {

        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('create_proposal')
            ->seePageIs('/create');

               }

    /**
     * Test for Auctions page
     * User-Auctions page
     * @return void
     */

    public function testAuctionsPage()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('Create auction')
            ->seePageIs('/create');

    }

    /**
     * Test for Create Auction
     * User-Auctions page
     * @return void
     */

    public function testCreateAuction()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('Create auction')
            ->type('Auctfwafawfawf1', 'title')
            ->type('blabdwadwafwafwafwwafwafwadwafwagggwafawwala', 'description')
            ->select('1 ','skill')
            ->select('1 ','faculty')
            ->type('1', 'days')
            ->type('1', 'hours')
            ->type('1', 'minutes')

            ->type('20181220', 'announce')
            ->type('20181224', 'due')
           // ->key('#due', '20181224')

            ->check('public_prop')
            ->check('public_bid')
            ->press('Create')
            ->seePageIs('/proposal/1');

    }



    /**
     * Test Team Pages
     *
     * @return void
     */

    public function testTeamPage()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('My Teams')
            ->seePageIs('/team');

    }


    /**
     * Test History Page
     *
     * @return void
     */

    public function testHistoryPage()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('History')
            ->seePageIs('/history');

    }

    /**
     * Test Proposals I won Page
     *
     * @return void
     */

    public function testProposalsIWonPage()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('Proposals I won')
            ->seePageIs('/proposalsIWon');

    }


    /**
     * Test Proposals I'm in page
     *
     * @return void
     */

    public function testProposalsImInPage()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('Proposals I\'m in')
            ->seePageIs('/proposals_im_in');

    }

    /**
     * Test MyProposals page
     *
     * @return void
     */

    public function testMyProposalsPage()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('My Proposals')
            ->seePageIs('/myproposals');

    }


    /**
     * Test see all proposals page
     *
     * @return void
     */

    public function testAllProposals()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('Proposals')
            ->seePageIs('/allproposals');

    }

    /**
     * Test see all people page
     *
     * @return void
     */

    public function testAllPeople()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('People')
            ->seePageIs('/people');

    }


    /**
     * Test see faq page
     *
     * @return void
     */

    public function testFaqPage()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('FAQ')
            ->seePageIs('/faq');


    }


    /**
     * Test see about page
     *
     * @return void
     */

    public function testAboutPage()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('About')
            ->seePageIs('/about');

    }


    /**
     * Test see contact page
     *
     * @return void
     */

    public function testContactPage()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('Contact')
            ->seePageIs('/contact');

    }

    /**
     * Test send msg to contact
     *
     * @return void
     */

    public function testContactPageMsg()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('Contact')
            ->type('Teste', 'name')
            ->type('Teste@fe.up.pt', 'email')
            ->type('Teste', 'message')
            ->press('contactSubmitButton')
            ->see('Your message was sent. Within 48h, you should receive a reply in your e-mail:')
            ->dontSee('Rails');

    }

    /**
     * Test add new team
     *
     * @return void
     */

    public function testAddTeam()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('My Teams')
            ->click('New Team')
            ->type('Teste', 'teamName')
            ->select('1', 'teamFaculty[]')
            ->type('dwfesfsegesgsegsgegs','teamDescription')
            ->press('Create team')
            ->seePageIs('/team/1');

    }

    public function testDeleteTeam()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('My Teams')
            ->click('New Team')
            ->type('Teste2', 'teamName')
            ->select('1', 'teamFaculty[]')
            ->type('dwfesfsegesgsegsgegs','teamDescription')
            ->press('Create team')
            ->click('Delete team')
            ->press('Delete')
            ->seePageIs('/team');

    }

    /**
     * Test add member to team
     *
     * @return void
     */

    public function testAddMemberTeam()
    {
        $user = factory(\App\User::class)->create();
        $user2 = factory(\App\User::class)->create();
        $this->actingAs($user)
            ->visit('/home')
            ->click('navbarDropdownMenuLink2')
            ->click('My Teams')
            ->click('New Team')
            ->type('Teste3', 'teamName')
            ->select('1', 'teamFaculty[]')
            ->type('dwfesfsegesgsdegsgegs','teamDescription')
            ->press('Create team')
            ->click('Add member')
            ->type(''.$user2->username,'username')
            ->press('Add')
            ->see(''.$user2->username);




    }





}