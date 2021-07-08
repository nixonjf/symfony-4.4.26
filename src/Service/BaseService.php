<?php

namespace App\Service;

use App\Helper\RouterTrait;
use App\Helper\TranslatorTrait;
use App\Helper\RequestStackTrait;
use App\Helper\SecurityUserTrait;

class BaseService
{
    use RouterTrait;
    use SecurityUserTrait;
    use TranslatorTrait;
    use RequestStackTrait;

    /**
     * Function to get special type block data for displaying it in front end.
     *
     * @param string $blockName
     * @param string $area
     * @param string $areaName
     * @param string $bgImage
     * @param string $buttonName
     *
     * @return array
     */
    public function specialBlock(string $blockName, string $area, string $areaName, string $bgImage, string $buttonName): array
    {
        $blockData['type'] = 'special';
        $blockData['name'] = $blockName;
        $blockData['image'] = $bgImage;
        $blockData['button'] = $buttonName;
        $blockData[$area] = $areaName;

        return $blockData;
    }

    /**
     * Function to get image type block data for displaying it in front end.
     *
     * @param string $imagePath
     *
     * @return array
     */
    public function imageBlock(string $imagePath): array
    {
        $blockData['type'] = 'image';
        $blockData['image'] = $imagePath;

        return $blockData;
    }

    /**
     * Function to get text type block data for displaying it in front end.
     *
     * @param string $header
     * @param string $text
     *
     * @return array
     */
    public function textBlock(string $header, string $text): array
    {
        $blockData['type'] = 'text';
        $blockData['header'] = $header;
        $blockData['text'] = $text;

        return $blockData;
    }

    /**
     * Function to get special parameter type block data for displaying it in front end.
     *
     * @param string $blockName
     * @param string $parameter
     * @param string $path
     * @param string $bgImage
     * @param string $buttonName
     *
     * @return array
     */
    public function parameterBlock(string $blockName, string $parameter, string $path, string $bgImage, string $buttonName): array
    {
        $blockData['type'] = 'special';
        $blockData['name'] = $blockName;
        $blockData['parameter'] = $parameter;
        $blockData['path'] = $path;
        $blockData['image'] = $bgImage;
        $blockData['button'] = $buttonName;

        return $blockData;
    }

    /**
     * Function to get home tiles type block data for displaying it in front end.
     *
     * @param string $transKey
     * @param string $area
     * @param string $areaName
     * @param string $bgImage
     * @param int    $notificationCount
     *
     * @return array
     */
    public function homeBlock(string $transKey, string $area, string $areaName, string $bgImage, int $notificationCount = 0): array
    {
        $blockData['type'] = 'home';
        $blockData['name'] = $this->getTranslator()->trans($transKey);
        $blockData['image'] = $bgImage;
        $blockData[$area] = $areaName;
        $blockData['path'] = $this->getRoute($areaName, ['_locale' => $this->getLocale()]);
        $blockData['notificationCount'] = $notificationCount;

        return $blockData;
    }

    /**
     * Function to get generic tile type block data for displaying in front end.
     *
     * @param string $type
     * @param string $transKey
     * @param string $path
     * @param string $parameter
     * @param string $bgImage
     * @param int    $notificationCount
     *
     * @return array
     */
    public function genericBlock(string $type, string $transKey, string $path, string $parameter, string $bgImage, int $notificationCount = 0): array
    {
        $blockData['type'] = $type;
        $blockData['name'] = $this->getTranslator()->trans($transKey, [], 'trans');
        $blockData['image'] = $bgImage;
        $blockData['parameter'] = $parameter;
        $blockData['path'] = $path;
        $blockData['notificationCount'] = $notificationCount;

        return $blockData;
    }

    /**
     * Function to get special policy type block data for displaying it in front end.
     *
     * @param string $blockName
     * @param string $parameter
     * @param string $path
     * @param string $bgImage
     * @param string $buttonName
     *
     * @return array
     */
    public function policyBlock(string $blockName, string $parameter, string $path, string $bgImage, string $buttonName): array
    {
        $blockData = $this->parameterBlock($blockName, $parameter, $path, $bgImage, $buttonName);
        $blockData['type'] = 'policy';

        return $blockData;
    }

    /**
     * Function to get special profile type block data for displaying it in front end.
     *
     * @param string $blockName
     * @param int    $contactId
     * @param string $path
     * @param string $bgImage
     * @param string $buttonName
     *
     * @return array
     */
    public function profileBlock(string $blockName, int $contactId, string $path, string $bgImage, string $buttonName): array
    {
        $blockData['type'] = 'special';
        $blockData['name'] = $blockName;
        $blockData['contactId'] = $contactId;
        $blockData['path'] = $path;
        $blockData['image'] = $bgImage;
        $blockData['button'] = $buttonName;

        return $blockData;
    }
}
