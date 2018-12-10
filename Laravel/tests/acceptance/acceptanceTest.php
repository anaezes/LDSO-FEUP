<?php

namespace Tests\acceptance;

use Tests\TestCase;

class AcceptanceTests extends TestCase
{


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/home')
            ->see('Welcome to U.OPENLAB!')
            ->dontSee('Rails');
    }

    /**
     * Test for register user.
     *
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
     * Test for logout.
     *
     * @return void



    public function testLogoutUser()
    {
        $this->visit('/home')
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
     */

}