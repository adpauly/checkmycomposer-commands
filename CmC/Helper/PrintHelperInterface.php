<?php

namespace CmC\Helper;

interface PrintHelperInterface
{
    /**
     * Print table with all requirements
     * @param  ConsoleOutput $output
     * @param  array $requirements
     */
    public static function printTable($output, array $requirements);

    /**
     * Print headers of table
     * @param  ConsoleOutput $output
     * @param  array $headers Headers labels
     * @param  int $maxWidths Max widths for each cell depending on length properties/headers labels
     */
    public static function printHeaders($output, $headers, $maxWidths);

    /**
     * Print horizontal separator
     * @param  ConsoleOutput $output
     * @param  array $headers Headers labels
     * @param  int $maxWidths Max widths for each cell depending on length properties/headers labels
     */
    public static function printHorizontalSeparator($output, $headers, $maxWidths);

    /**
     * Returns max width between all properties and headers labels
     * @param  array $valuesProperties Contains all values which will be printed (for each property/column)
     *
     * @return int The biggest width between all values printed
     */
    public static function maxWidths(array $valuesProperties);

    /**
     * Returns the first line of table's header
     * @return array Associative array containing property as key and header/column label as value
     */
    public static function getFirstLineHeaders();

    /**
     * Returns the first line of table's header
     * @return array Associative array containing property as key and header/column label as value
     * @todo Factorise this function and the one above
     */
    public static function getScndLineHeaders();

    /**
     * Returns the label of each type of status
     * @return string Label status
     */
    public static function getLabelStatuses();
}