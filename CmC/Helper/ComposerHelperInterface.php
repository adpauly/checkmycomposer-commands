<?php

namespace CmC\Helper;

use Composer\DependencyResolver\Pool;

interface ComposerHelperInterface
{
    /**
     * Get latest package for each requirement
     *
     * @return Array
     */
    public static function getLatestPackages(array $allRequirements, $stability = 'stable', $sourceRepo = null);

    /**
     * Get installed packages in local repository
     *
     * @return Array
     */
    public static function getInstalledPackages();

    /**
     * Get latest package for the specified requirement
     *
     * @param Array $requirement
     * @param String $stability
     * @param $sourceRepo
     *
     * @return Array
     */
    public static function getLatestPackage($requirement, Pool $pool);

    /**
     * @throws JsonValidationException, InvalidArgumentException
     *
     * @return \Composer\Composer
     */
    public static function getComposer();

    /**
     * Get the final content of the composer.json file
     *
     * @return Array Content of the composer.json file as array
     */
    public static function readFile($path);
}


