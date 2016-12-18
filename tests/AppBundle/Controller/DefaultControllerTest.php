<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Test\Functional\ControllerTestCase;
use Symfony\Component\Yaml\Yaml;

class DefaultControllerTest extends ControllerTestCase
{
    public function testIndex()
    {
        $client = $this->createNewClient();

        $crawler = $this->requestRoute("homepage");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
