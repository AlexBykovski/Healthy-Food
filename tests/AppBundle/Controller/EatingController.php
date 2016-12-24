<?php
/**
 * Created by PhpStorm.
 * User: aleksander
 * Date: 24.12.16
 * Time: 20:15
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;

class EatingController extends WebTestCase
{
    public function testEatingListAction()
    {
        $client = $this->createNewClient();

        $crawler = $this->requestRoute("eating_list");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}