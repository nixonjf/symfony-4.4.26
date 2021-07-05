<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase {

    private $testUsername = '23w2admin@admin.com';
    private $testPassword = '221';

    public function setUp(): void {

        parent::setUp();
    }

    /**
     * test getPagesAction
     */
    public function testRegister() {

        $client = static::createClient();
        $client->request(
                'POST', '/api/register', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode(array(
            'username' => $this->testUsername,
            'password' => $this->testPassword,
                ))
        );
        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode(), 'Status Code = 200');

        return $client;
    }

    /**
     * test login page
     */
    public function testLogin() {

        $client = static::createClient();
        $client->request(
                'POST', '/api/login_check', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode(array(
            'username' => $this->testUsername,
            'password' => $this->testPassword,
                ))
        );
        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode(), 'Status Code = 200');

        return $client;
    }

    /**
     * Create a client with a default Authorization header. 
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient() {

        $client = static::createClient();
        $client->request(
                'POST', '/api/login_check', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode(array(
            'username' => $this->testUsername,
            'password' => $this->testPassword,
                ))
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode(), 'Status Code = 200');

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    /**
     * test getPagesAction
     */
    public function testIsAuthenticatedUsingToken() {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/sample');

        $response = $client->getResponse();

        $this->deleteApiUser();
        $this->assertSame(200, $response->getStatusCode(), 'Status Code = 200');

        return $client;
    }

    /**
     * Deletes the newly created api user.
     * 
     * @param string $userName
     */
    protected function deleteApiUser(): void {
        $kernel = self::bootKernel();

        $kernel->getContainer()
                ->get('doctrine')
                ->getManager()->getConnection()->createQueryBuilder()->delete('user')
                ->where('email = :email')
                ->setParameter(':email', $this->testUsername)->execute();
    }

    protected function tearDown(): void {


        parent::tearDown();
    }

}
