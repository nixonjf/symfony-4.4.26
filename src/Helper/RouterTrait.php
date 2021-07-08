<?php

namespace App\Helper;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait RouterTrait
{
    /** @var UrlGeneratorInterface */
    private $router;

    /**
     * @param UrlGeneratorInterface $router
     *
     * @required
     */
    public function setRoute(UrlGeneratorInterface $router): void
    {
        $this->router = $router;
    }

    /**
     * Function to return the route path.
     *
     * @param mixed $routeName
     * @param mixed $params
     * 
     * @return string
     */
    public function getRoute($routeName, $params = []): string
    {
        return $this->router->generate($routeName, $params, UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
