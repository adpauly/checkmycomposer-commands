<?php

namespace CmC\Helper;

class PrintHelper implements PrintHelperInterface
{
     /**
     * {@inheritDoc}
     */
    public static function printTable($output, array $requirements)
    {
        if (count($requirements)) {
            $firstLineHeaders = static::getFirstLineHeaders();
            $scndLineHeaders = static::getScndLineHeaders();
            $maxWidths = static::maxWidths(array_merge(array('firstLineHeaders' => $firstLineHeaders), array('scndLineHeaders' => $scndLineHeaders), $requirements));
            $typesOfStatus = static::getLabelStatuses();

            static::printHeaders($output, $firstLineHeaders, $maxWidths);
            static::printHeaders($output, $scndLineHeaders, $maxWidths);
            static::printHorizontalSeparator($output, $firstLineHeaders, $maxWidths);

            foreach ($requirements as $requirement) {
                $line = '| ';

                foreach ($requirement as $property => $value) {
                    if ($property == 'status' || $property == 'statusInConstraints') {
                        $statusString = str_pad($value, $maxWidths[$property], ' ', STR_PAD_RIGHT).'';
                        $line .= $value == $typesOfStatus[-1] ? sprintf('<comment>%s</comment>', $statusString) : sprintf('%s', $statusString);
                        $line .= ' | ';
                    } else {
                        $line .= sprintf('%s | ', str_pad($value, $maxWidths[$property], ' ', STR_PAD_RIGHT));
                    }
                }

                $output->writeLn($line);
            }
        } else {
            $ouput->writeLn('No packages found.');
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function printHeaders($output, $headers, $maxWidths)
    {
        $line = '| ';
        foreach ($headers as $key => $headerValue) {
            $line .= sprintf('<info>%s</info> | ', str_pad($headerValue, $maxWidths[$key], ' ', STR_PAD_RIGHT));
        }

        $output->writeLn($line);
    }

    /**
     * {@inheritDoc}
     */
    public static function printHorizontalSeparator($output, $headers, $maxWidths)
    {
        $totalMaxWidths = array_sum($maxWidths);
        $totalMaxWidths += count($maxWidths); // For each '|' separator
        $totalMaxWidths++; // For first '|' separator

        $line = '| ';
        foreach ($headers as $key => $headerValue) {
            $line .= sprintf('%s + ', str_pad('', $maxWidths[$key], '-', STR_PAD_RIGHT));
        }

        $line = substr($line, 0, strlen($line)-2); // -2 = "+ " string
        $line .= '|';

        $output->writeLn($line);
    }

    /**
     * {@inheritDoc}
     */
    public static function maxWidths(array $valuesProperties)
    {
        // Copy all property keys of requirements
        $maxWidths = array_fill_keys(array_keys($valuesProperties['firstLineHeaders']), 0);

        foreach ($valuesProperties as $requirement) {
            foreach ($maxWidths as $property => $maxWidth) {
                if (strlen($requirement[$property]) > $maxWidth) {
                    $maxWidths[$property] = strlen($requirement[$property]);
                }
            }
        }

        return $maxWidths;
    }

    /**
     * {@inheritDoc}
     */
    public static function getFirstLineHeaders()
    {
        return array(
            'packageName' => '',
            'currentVersion' => '',
            'latestVersionInConstraints' => 'Last version',
            'statusInConstraints' => 'Status',
            'latestVersion' => '',
            'status' => '',
        );
    }

    /**
     * {@inheritDoc}
     */
    public static function getScndLineHeaders()
    {
        return array(
            'packageName' => 'Package name',
            'currentVersion' => 'Current version',
            'latestVersionInConstraints' => 'in constraints',
            'statusInConstraints' => 'in constraints',
            'latestVersion' => 'Last version',
            'status' => 'Status',
        );
    }

    /**
     * {@inheritDoc}
     */
    public static function getLabelStatuses()
    {
        return array(
            -1 => 'outdated',
            0 => 'up to date',
            1 => 'up to date',
        );
    }
}