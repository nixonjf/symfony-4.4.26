<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Util\Constants;

trait RequestStackTrait
{
    /** @var RequestStack */
    private $request;

    /**
     * @required
     *
     * @param RequestStack $request
     */
    public function setRequest(RequestStack $request): void
    {
        $this->request = $request;
    }

    /**
     * Function to return current locale.
     *
     * @return string
     */
    public function getLocale(): string
    {
        if(null!== $this->request->getCurrentRequest()){
            return $this->request->getCurrentRequest()->getLocale();
        }
        return Constants::DEFAULT_LOCALE;
    }
}
