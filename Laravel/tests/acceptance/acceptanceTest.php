<?php

namespace Tests\acceptance;

use Tests\TestCase;

class AcceptanceTests extends TestCase
{


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
        $this->visit('/register')
            ->type('Teste', 'name')
            ->type('teste', 'username')
            ->type('teste@fe.up.pt', 'email')
            ->select('2','idfaculty')
            ->type('919191919', 'phone')
            ->type('123456', 'password')
            ->type('123456', 'password_confirmation')
            ->press('REGISTER')
            ->seePageIs('/faq');
    }


    /**
     * Test for access profile page.
     *  Member-Profile page
     * @return void
*/

    public function testAccessProfile()
    {

        $this->visit('/home')
            ->visit('Login')
            ->type('teste', 'username')
            ->type('123456', 'password')
            ->press('LOGIN')
            ->visit('Profile')
            ->seePageIs('/home');

    }


    /**
     * Test for edit profile page.
     *
     * @return void
    */

    public function testEditProfile()
    {
        $this->visit('/profile/1')
            ->visit('Edit Info')
            ->type('Teste1', 'name')
            ->type('teste1@fe.up.pt', 'email')
            ->visit('Save any new changes')
            ->seePageIs('home');


    }

    /**
     * Test for logout.
     *  Member-Logout
     * @return void
     */

    public function testLogoutUser()
    {
        $this->visit('/home')
            ->visit('Logout')
            ->seePageIs('/home');


    }


    /**
     * Test for login user.
     *  Visitor-Authentication
     * @return void
     */

    public function testLoginUser()
    {
        $this->visit('/home')
            ->visit('Login')
            ->type('teste', 'username')
            ->type('123456', 'password')
            ->press('LOGIN')
            ->seePageIs('/home');


    }

    /**
     * Test for Auctions page
     * User-Auctions page
     * @return void
     */

    public function testAuctionsPage()
    {
        $this->visit('/home')
            ->visit('allproposals')
            ->seePageIs('/allproposals');

    }



    /**
     * Test Team Pages
     *
     * @return void
     */

    public function testTeamPage()
    {
        $this->visit('/home')
            ->visit('team')
            ->seePageIs('/home');

    }


    /**
     * Test History Page
     *
     * @return void
     */

    public function testHistoryPage()
    {
        $this->visit('/home')
            ->visit('/history')
            ->seePageIs('/home');

    }

    /**
     * Test Proposals I won Page
     *
     * @return void
     */

    public function testProposalsIWonPage()
    {
        $this->visit('/home')
            ->visit('/proposalsIWon')
            ->seePageIs('/home');

    }


    /**
     * Test Proposals I'm in page
     *
     * @return void
     */

    public function testProposalsImInPage()
    {
        $this->visit('/home')
            ->visit('/proposals_im_in')
            ->seePageIs('/home');

    }

    /**
     * Test MyProposals page
     *
     * @return void
     */

    public function testMyProposalsPage()
    {
        $this->visit('/home')
            ->visit('/myproposals')
            ->seePageIs('/home');

    }







}