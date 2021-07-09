<?php

namespace App\Tests\Controller\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiAuthTest extends WebTestCase {

    private $testUsername;
    private $testPassword;

    public function setUp(): void {

        parent::setUp();
        $this->testUsername = $this->generateRandomString(4) . '@test.ch';
        $this->testPassword = $this->generateRandomString(4);
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

        return ['testUsername' => $this->testUsername, 'testPassword' => $this->testPassword];
    }

    /**
     * 
     * @depends testRegister
     * 
     * test login page
     */
    public function testLogin($credentials) {

        $client = static::createClient();
        $client->request(
                'POST', '/api/login_check', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode(array(
            'username' => $credentials['testUsername'],
            'password' => $credentials['testPassword'],
                ))
        );


        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode(), 'Status Code = 200');



        $data = json_decode($response->getContent(), true);


        $this->assertArrayHasKey('token', $data);


        return ['testUsername' => $credentials['testUsername'], 'token' => $data['token']];
    }

    /**
     * 
     * 
     * @depends testLogin
     * 
     * test getPagesAction
     */
    public function testIsAuthenticatedUsingToken($credentials) {

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $credentials['token']));

        $client->request('GET', '/api/index');

        $response = $client->getResponse();

        $this->deleteApiUser($credentials);
        $this->assertSame(200, $response->getStatusCode(), 'Status Code = 200');

        return $client;
    }

    /**
     * Deletes the newly created api user.
     * 
     * @depends testRegister
     * 
     * @param string $credentials
     */
    protected function deleteApiUser($credentials): void {
        $kernel = self::bootKernel();

        $kernel->getContainer()
                ->get('doctrine')
                ->getManager()->getConnection()->createQueryBuilder()->delete('user')
                ->where('email = :email')
                ->setParameter(':email', $credentials['testUsername'])->execute();
    }

    /**
     * Generates a random secure password.
     *
     * @param int $length
     * 
     * @return string
     */
    protected function generateRandomString($length = 10): string {
        return mb_substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ

        ', ceil($length / mb_strlen($x)))), 1, $length);
    }

    protected function tearDown(): void {

        parent::tearDown();
    }

}
