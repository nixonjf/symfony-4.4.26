<?php

namespace App\Tests\Controller;

use Symfony\Component\BrowserKit\Cookie;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthTest extends WebTestCase {

    private $testUsername = 'random@admin.com';
    private $testPassword = '221';
    private $client = null;

    public function setUp(): void {

        parent::setUp();
        $this->client = static::createClient();
        $this->testUsername = $this->generateRandomString(5) . '@test.ch';
        $this->testPassword = $this->generateRandomString(6);
    }

    public function testVisitingWhileLoggedIn() {

        $this->createAuthenticatedClient($this->testUsername, $this->testPassword);
        $client = static::createClient();
        // get or create the user somehow (e.g. creating some users only
        // for tests while loading the test fixtures)
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail($this->testUsername);

        $token = $this->logIn($testUser, $this->testPassword);

        $client->request(
                'POST', '/api/login_check', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode(array(
            'username' => $testUser->getEmail(),
            'password' => $this->testPassword,
                ))
        );

        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true);

        //$client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));


        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
       
        // user is now logged in, so you can test protected resources
//        $client->request('GET', '/admin');

        $client = static::createClient([], [
                    'PHP_AUTH_USER' => $testUser->getEmail(),
                    'PHP_AUTH_PW' => $this->testPassword,
        ]);
        
        

        $client->request('GET', '/admin');

//        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();
        print_r($response);
        ;
        die;
        $this->assertSame(200, $response->getStatusCode(), 'Status Code = 200');
//        $this->assertSelectorTextContains('h1', 'Hello Username!');
    }

    private function logIn($testUser, $pass) {
        $session = self::$container->get('session');

        $kernel = self::bootKernel();


        $em = $kernel->getContainer()
                ->get('doctrine')
                ->getManager();

        $queryBuilder = $kernel->getContainer()
                        ->get('doctrine')
                        ->getManager()->getConnection()->createQueryBuilder();

        $testUser->setRoles(["ROLE_ADMIN"]);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($testUser);

        // actually executes the queries (i.e. the INSERT query)
        ;



        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'secured_area';

        $client = static::createClient();
        $client->request(
                'POST', '/api/login_check', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode(array(
            'username' => $testUser->getEmail(),
            'password' => $pass,
                ))
        );

        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true);

        //$client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $data['token'];

//         dump($response); die;
//        $cookie = new Cookie($session->getName(), $session->getId());
//
//        $this->client->getCookieJar()->set($cookie);
    }

    /**
     * Create a client with a default Authorization header. 
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($testUsername, $testPassword) {

        $client = static::createClient();
        $client->request(
                'POST', '/api/register', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode(array(
            'username' => $testUsername,
            'password' => $testPassword,
                ))
        );
        return ['testUsername' => $testUsername, 'testPassword' => $testPassword];
    }

    protected function tearDown(): void {

        parent::tearDown();
    }

    /**
     * Generates a random secure password.
     *
     * @param int $length
     * 
     * @return string
     */
    protected function generateRandomString($length = 10): string {
        return mb_substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / mb_strlen($x)))), 1, $length);
    }

}
