<?php

namespace App\Helper;

use Symfony\Contracts\Translation\TranslatorInterface;

trait TranslatorTrait
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * @required
     *
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    /**
     * Function to return translation interface.
     *
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }
}
