<?php

namespace AppBundle\Test\Functional;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ControllerTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @return Client
     */
    public function createNewClient(){
        $this->setClient(self::createClient());

        return $this->getClient();
    }

    /**
     * @return Client
     */
    public function getClient(){
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client){
        $this->client = $client;
    }

    /**
     * @return Crawler
     */
    public function requestRoute($routeName, $params = [], $method = 'GET'){
        $client = $this->client;

        $url = $client->getContainer()->get('router')->generate($routeName,
            $params);

        return $client->request($method, $url);
    }

    /**
     * @return EntityManager
     */
    public function getEnityManager(){
        return $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function loginUser($email = "login_action@test.com", $password = "12312312"){
        $client = $this->getClient();

        $client->request('GET', "/logout");

        $crawler = $this->requestRoute("login");

        $formAuthorisation = $crawler->selectButton('Авторизоваться')->form();
        $formAuthorisation['_username'] = $email;
        $formAuthorisation['_password'] = $password;

        $client->submit($formAuthorisation);
        $client->followRedirect();
    }
}