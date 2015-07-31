<?php

namespace CmC\Helper;

interface ParserHelperInterface
{
    /**
     * Call parseStatus function to parse "status" property for each requirements
     * @param array $requirements
     * @param array $statusesProperties status properties
     *
     * @return $requirements
     */
    public static function parseStatuses(array $requirements, array $statusesProperties);

    /**
     * Parse status (1|0|-1) into readable string ("up to date"|"outdated")
     * @param array $requirements
     * @param string $statusProperty Name of status property
     *
     * @return $requirements
     */
    public static function parseStatus(array $requirements, $statusProperty);

    /**
     * Unset specified property from array
     * @param array $requirements
     * @param string name of the property
     */
    public static function unsetProperty(array $requirements, $property);
}