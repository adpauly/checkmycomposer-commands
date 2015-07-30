<?php

namespace CmC\Helper;

class ParserHelper
{
    /**
     * Call parseStatus function for each "status property"
     * @param array $requirements
     * @param array $statutesProperties status properties
     *
     * @return $requirements
     */
    public static function parseStatutes($requirements, $statutesProperties)
    {
        foreach ($statutesProperties as $key => $statusProperty) {
            $requirements = static::parseStatus($requirements, $statusProperty);
        }

        return $requirements;
    }

    /**
     * Parse status (0|-1) into readable string ("up to date"|"outdated")
     * @param array $requirements
     * @param string $statusProperty Name of status property
     *
     * @return $requirements
     */
    public static function parseStatus($requirements, $statusProperty)
    {
        $typesOfStatus = PrintHelper::getTypesOfStatus();

        foreach ($requirements as $key => $requirement) {
            if (isset($requirement[$statusProperty])) {
                $requirements[$key][$statusProperty] = $typesOfStatus[$requirement[$statusProperty]];
            } else {
                $requirements[$key][$statusProperty] = '/';
            }
        }

        return $requirements;
    }

    /**
     * Unset specified property from array
     * @param array $requirements
     * @param string name of the property
     */
    public static function unsetProperty($requirements, $property)
    {
        foreach ($requirements as $key => $requirement) {
            unset($requirements[$key][$property]);
        }

        return $requirements;
    }
}