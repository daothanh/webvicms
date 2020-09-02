<?php

namespace Modules\Media\Image\Helpers;

use Illuminate\Support\Str;

class FileHelper
{
    /**
     * Get first token of string before delimiter
     * @param $mimetype
     * @return string
     */
    public static function getTypeByMimetype($mimetype)
    {
        return strtok($mimetype, '/');
    }

    /**
     * Get Font Awesome icon for various files
     * @param string $mediaType
     * @return string
     */
    public static function getFaIcon($mediaType, $extension = null)
    {
        switch ($mediaType) {
            case 'video':
                $icon = 'zmdi zmdi-videocam zmdi-hc-fw';
                break;
            case 'audio':
                $icon = 'zmdi zmdi-audio zmdi-hc-fw';
                break;
            case 'image':
                $icon = 'far fa-file-image';
                break;
            case 'application':
                $icon = 'far fa-file' . ($extension ? '-' . $extension : '');
                break;
            default:
                $icon = 'far fa-file';
        }
        return $icon;
    }

    public static function slug($name)
    {
        $extension = self::getExtension($name);
        $name = str_replace($extension, '', $name);

        $name = Str::slug($name);

        return $name . strtolower($extension);
    }

    public static function filesize($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    /**
     * Get the extension from the given name
     * @param $name
     * @return string
     */
    private static function getExtension($name)
    {
        return substr($name, strrpos($name, '.'));
    }
}
