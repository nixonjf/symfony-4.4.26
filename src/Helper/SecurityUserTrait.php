<?php

namespace App\Helper;

use App\Security\User\WebserviceUser;
use Symfony\Component\Security\Core\Security;

trait SecurityUserTrait
{
    /** @var Security */
    private $user;

    /**
     * @required
     *
     * @param Security $security
     */
    public function setUser(Security $security): void
    {
        $this->user = $security->getUser();
    }

    /**
     * Function to return logged in user details.
     *
     * @return Security
     */
    public function getUser(): ?WebserviceUser
    {
        return $this->user;
    }
}
