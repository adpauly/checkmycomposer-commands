<?php

namespace CmC\Helper;

class ParserHelper
{
    /**
     * {@inheritDoc}
     */
    public static function parseStatuses(array $requirements, array $statusesProperties)
    {
        foreach ($statusesProperties as $key => $statusProperty) {
            $requirements = static::parseStatus($requirements, $statusProperty);
        }

        return $requirements;
    }

    /**
     * {@inheritDoc}
     */
    public static function parseStatus(array $requirements, $statusProperty)
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
     * {@inheritDoc}
     */
    public static function unsetProperty(array $requirements, $property)
    {
        foreach ($requirements as $key => $requirement) {
            unset($requirements[$key][$property]);
        }

        return $requirements;
    }
}