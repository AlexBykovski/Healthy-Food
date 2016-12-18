<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Test\Functional\ControllerTestCase;

class SecurityControllerTest extends ControllerTestCase
{
    public function testRegistrationAction()
    {
        $client = $this->createNewClient();

        $this->requestRoute("registration");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testLoginAction()
    {
        $client = $this->createNewClient();

        $this->requestRoute("login");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}