<?php
/**
 * PHP Exif Exiftool Mapper
 *
 * @link        http://github.com/miljar/PHPExif for the canonical source repository
 * @copyright   Copyright (c) 2015 Tom Van Herreweghe <tom@theanalogguy.be>
 * @license     http://github.com/miljar/PHPExif/blob/master/LICENSE MIT License
 * @category    PHPExif
 * @package     Mapper
 */

namespace PHPExif\Mapper;

use PHPExif\Exif;
use DateTime;

/**
 * PHP Exif Exiftool Mapper
 *
 * Maps Exiftool raw data to valid data for the \PHPExif\Exif class
 *
 * @category    PHPExif
 * @package     Mapper
 */
class Exiftool implements MapperInterface
{
    const APERTURE = 'Aperture';
    const APPROXIMATEFOCUSDISTANCE = 'ApproximateFocusDistance';
    const ARTIST = 'Artist';
    const CAPTION = 'Caption';
    const CAPTIONABSTRACT = 'Caption-Abstract';
    const COLORSPACE = 'ColorSpace';
    const COPYRIGHT = 'Copyright';
    const CREATIONDATE = 'CreationDate';
    const CREDIT = 'Credit';
    const EXPOSURETIME = 'ExposureTime';
    const FILESIZE = 'FileSize';
    const FOCALLENGTH = 'FocalLength';
    const HEADLINE = 'Headline';
    const IMAGEHEIGHT = 'ImageHeight';
    const IMAGEWIDTH = 'ImageWidth';
    const ISO = 'ISO';
    const JOBTITLE = 'JobTitle';
    const KEYWORDS = 'Keywords';
    const MIMETYPE = 'MIMEType';
    const MODEL = 'Model';
    const ORIENTATION = 'Orientation';
    const SOFTWARE = 'Software';
    const SOURCE = 'Source';
    const TITLE = 'Title';
    const XRESOLUTION = 'XResolution';
    const YRESOLUTION = 'YResolution';

    /**
     * Maps the ExifTool fields to the fields of
     * the \PHPExif\Exif class
     *
     * @var array
     */
    protected $map = array(
        self::APERTURE => Exif::APERTURE,
        self::ARTIST => Exif::AUTHOR,
        self::MODEL => Exif::CAMERA,
        self::CAPTION => Exif::CAPTION,
        self::CAPTIONABSTRACT => Exif::CAPTION,
        self::COLORSPACE => Exif::COLORSPACE,
        self::COPYRIGHT => Exif::COPYRIGHT,
        self::CREATIONDATE => Exif::CREATION_DATE,
        self::CREDIT => Exif::CREDIT,
        self::EXPOSURETIME => Exif::EXPOSURE,
        self::FILESIZE => Exif::FILESIZE,
        self::FOCALLENGTH => Exif::FOCAL_LENGTH,
        self::APPROXIMATEFOCUSDISTANCE => Exif::FOCAL_DISTANCE,
        self::HEADLINE => Exif::HEADLINE,
        self::IMAGEHEIGHT => Exif::HEIGHT,
        self::XRESOLUTION => Exif::HORIZONTAL_RESOLUTION,
        self::ISO => Exif::ISO,
        self::JOBTITLE => Exif::JOB_TITLE,
        self::KEYWORDS => Exif::KEYWORDS,
        self::MIMETYPE => Exif::MIMETYPE,
        self::ORIENTATION => Exif::ORIENTATION,
        self::SOFTWARE => Exif::SOFTWARE,
        self::SOURCE => Exif::SOURCE,
        self::TITLE => Exif::TITLE,
        self::YRESOLUTION => Exif::VERTICAL_RESOLUTION,
        self::IMAGEWIDTH => Exif::WIDTH,
    );

    /**
     * Maps the array of raw source data to the correct
     * fields for the \PHPExif\Exif class
     *
     * @param array $data
     * @return void
     */
    public function mapRawData(array $data)
    {
        $mappedData = array();
        foreach ($data as $field => $value) {
            if (!array_key_exists($field, $this->map)) {
                // silently ignore unknown fields
                continue;
            }

            $key = $this->map[$field];

            // manipulate the value if necessary
            switch ($field) {
                case self::APERTURE:
                    $value = sprintf('f/%01.1f', $value);
                    break;
                case self::APPROXIMATEFOCUSDISTANCE:
                    $value = sprintf('%1$sm', $value);
                    break;
                case self::CREATIONDATE:
                    $value = DateTime::createFromFormat('Y:m:d H:i:s', $value);
                    break;
                case self::EXPOSURETIME:
                    $value = '1/' . round(1 / $value);
                    break;
                case self::FOCALLENGTH:
                    $focalLengthParts = explode(' ', $value);
                    $value = (int) reset($focalLengthParts);
                    break;
            }

            // set end result
            $mappedData[$key] = $value;
        }

        return $mappedData;
    }
}

