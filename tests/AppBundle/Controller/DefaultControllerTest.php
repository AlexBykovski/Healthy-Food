<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Test\Functional\ControllerTestCase;
use Symfony\Component\Yaml\Yaml;

class DefaultControllerTest extends ControllerTestCase
{
    public function testIt()
    {
        $value = Yaml::parse("foo: bar");

        $this->assertSame(['foo' => 'bar'], $value);
        $this->assertSame(256, Yaml::PARSE_CONSTANT);
    }
    /*public function testIndex()
    {
        $client = $this->createNewClient();

        $crawler = $this->requestRoute("homepage");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assert('HEALTHY FOOD', $crawler->filter('#container h1')->text());
    }*/
}
