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

         Not working!
    public function testAccessProfile()
    {

        $this->visit('/home')
            ->visit('Login')
            ->type('teste', 'username')
            ->type('123456', 'password')
            ->press('LOGIN')
            ->visit('Profile')
            ->seePageIs('/profile/1');

    }
     */

    /**
     * Test for edit profile page.
     *
     * @return void


    public function testEditProfile()
    {
        $this->visit('/profile/1')
            ->press('Edit Info')
            ->type('Teste1', 'name')
            ->type('teste1@fe.up.pt', 'email')
            ->press('Save any new changes')
            ->seePageIs('');


    }
     */
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




}