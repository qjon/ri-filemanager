<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\Model;

/**
 * Class UploadedFileParameters
 *
 * @package RI\FileManagerBundle\Model
 */
class UploadedFileParametersModel implements \Serializable
{
    // File size unit types
    const FILE_SIZE_IN_B = 0;
    const FILE_SIZE_IN_KB = 1;
    const FILE_SIZE_IN_MB = 2;

    // File size units
    const B_UNIT = 'B';
    const KB_UNIT = 'KB';
    const MB_UNIT = 'MB';

    // Rounding param.
    const ROUNDING = 2;

    // Size unit divisors
    const KB_DIVISOR = 1024;
    const ONE_HUNDRED_KB = 102400;
    const MB_DIVISOR = 1048576;

    /**
     * @var integer
     */
    private $width = 0;

    /**
     * @var integer
     */
    private $height = 0;

    /**
     * @var string
     */
    private $mime;

    /**
     * @var integer
     */
    private $size = 0;

    /**
     * @var string
     */
    private $sizeNormalized = '';

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getSizeNormalize()
    {
        return $this->sizeNormalized;
    }

    /**
     * @param integer $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @param string $mime
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    /**
     * @param integer $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @param integer $size
     */
    public function setSize($size)
    {
        $this->size = $size;
        $this->sizeNormalized = $this->normalizeSize($size);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            'width' => $this->width,
            'height' => $this->height,
            'mime' => $this->mime,
            'size' => $this->size,
            'sizeNormalized' => $this->sizeNormalized
        ));
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->width = $data['width'];
        $this->height = $data['height'];
        $this->mime = $data['mime'];
        $this->size = $data['size'];
        $this->sizeNormalized = $data['sizeNormalized'];
    }


    /**
     * @param integer $size
     *
     * @return array
     */
    private function normalizeSize($size)
    {
        if ($size < self::KB_DIVISOR) {
            $sizeUnit = $this->getResourceSizeUnit(self::FILE_SIZE_IN_B);
        } elseif ($size < self::ONE_HUNDRED_KB) {
            $size = ($size / self::KB_DIVISOR);
            $sizeUnit = $this->getResourceSizeUnit(self::FILE_SIZE_IN_KB);
        } else {
            $size = ($size / self::MB_DIVISOR);
            $sizeUnit = $this->getResourceSizeUnit(self::FILE_SIZE_IN_MB);
        }

        return sprintf('%s %s', round($size, self::ROUNDING), $sizeUnit);
    }

    /**
     * Get size unit
     *
     * @param string $sizeUnit File size unit
     *
     * @return string
     */
    private function getResourceSizeUnit($sizeUnit)
    {
        $unit = null;

        switch ($sizeUnit) {
            case 0:
                $unit = self::B_UNIT;
                break;
            case 1:
                $unit = self::KB_UNIT;
                break;
            case 2:
                $unit = self::MB_UNIT;
                break;
        }

        return $unit;
    }

}