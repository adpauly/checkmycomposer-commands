<?php

namespace CmC\Helper;

use Composer\Json\JsonFile;
use Composer\Repository\CompositeRepository;
use Composer\Factory;
use Composer\IO\NullIO;
use Composer\DependencyResolver\Pool;
use Composer\Package\Version\VersionSelector;
use Composer\Config;
use Composer\Util\RemoteFilesystem;
use Composer\Package\CompletePackage;

class ComposerHelper implements ComposerHelperInterface
{
    private static $composer;

    /**
     * {@inheritDoc}
     */
    public static function getLatestPackages(array $allRequirements)
    {
        foreach ($allRequirements as $key => $requirement) {
            try {
                $latestPackages = static::getLatestPackage($requirement);
            } catch(\InvalidArgumentException $e) {
                $latestPackages = null;
            }

            $allRequirements[$key]['latestVersion'] = $latestPackages['latestPackage'] ? $latestPackages['latestPackage']->getVersion() : '/';
            $allRequirements[$key]['latestVersionInConstraints'] = $latestPackages['latestPackageInConstraints'] ? $latestPackages['latestPackageInConstraints']->getVersion() : '/';

            if ($latestPackages['latestPackage']) {
                $compare = version_compare($allRequirements[$key]['currentVersion'], $allRequirements[$key]['latestVersion']);
                $allRequirements[$key]['status'] = $compare;
            }
            if ($latestPackages['latestPackageInConstraints']) {
                $compare = version_compare($allRequirements[$key]['currentVersion'], $allRequirements[$key]['latestVersionInConstraints']);
                $allRequirements[$key]['statusInConstraints'] = $compare;
            }
        }

        return $allRequirements;
    }

    /**
     * {@inheritDoc}
     */
    public static function getLatestPackage($requirement, $stability = 'stable', $sourceRepo = null)
    {
        //echo PHP_EOL.microtime(true).PHP_EOL;
        if (!$sourceRepo) {
            $sourceRepo = new CompositeRepository(Factory::createDefaultRepositories(new NullIO()));
        }

        $pool = new Pool($stability);

        $pool->addRepository($sourceRepo);
        //echo microtime(true).PHP_EOL;
        // Find the latest package
        $versionSelector = new VersionSelector($pool);
        $latestPackage = $versionSelector->findBestCandidate($requirement['packageName'], null);
        $latestPackageInConstraints = $versionSelector->findBestCandidate($requirement['packageName'], $requirement['constraintsOfVersion']);

        // if (!$latestPackageInConstraints) {
        //     throw new \InvalidArgumentException("Could not find package {$requirement['packageName']}" . ($requirement['constraintsOfVersion'] ? " with version {$requirement['constraintsOfVersion']}." : " with stability $stability."));
        // }

        //echo microtime(true).PHP_EOL;
        return array(
            'latestPackage' => $latestPackage,
            'latestPackageInConstraints' => $latestPackageInConstraints,
        );
    }

    public static function getLatestPackage2($requirement, $stability = 'stable')
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://packagist.org/feeds/package.'.$requirement['packageName'].'.rss'); //http://local.cmc.com/
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        $response = simplexml_load_string($response);

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public static function getInstalledPackages()
    {
        $composer = static::getComposer();

        $installedRepo = static::getComposer()->getRepositoryManager()->getLocalRepository();
        $package = $composer->getPackage();
        $requires = $package->getRequires();

        $requirements = array();

        foreach ($requires as $name => $link) {
            $match = $installedRepo->findPackages($name);

            foreach ($match as $package) {
                if ($package instanceof CompletePackage) {
                    $requirements[] = array(
                        'packageName'                => $package->getName(),
                        'currentVersion'             => $package->getVersion(),
                        'latestVersionInConstraints' => null,
                        'constraintsOfVersion'       => $link->getConstraint()->getPrettyString(),
                        'statusInConstraints'        => null,
                        'latestVersion'              => null,
                        'status'                     => null,
                    );
                }
            }
        }

        return $requirements;
    }

    /**
     * {@inheritDoc}
     */
    public static function getComposer()
    {
        $io = new NullIo();

        if (null === static::$composer) {
            try {
                static::$composer = Factory::create($io);
            } catch (\InvalidArgumentException $e) {
                $message = $e->getMessage() . ':' . PHP_EOL . $errors;
                throw new InvalidArgumentException($message);
            } catch (JsonValidationException $e) {
                $errors = ' - ' . implode(PHP_EOL . ' - ', $e->getErrors());
                $message = $e->getMessage() . ':' . PHP_EOL . $errors;
                throw new JsonValidationException($message);
            }
        }

        return static::$composer;
    }

    /**
     * {@inheritDoc}
     */
    public static function readFile($path)
    {
        $composerFile = static::getJsonComposer($path);
        $contentComposer = $composerFile->read();

        return $contentComposer;
    }

    /**
     * Get a composer file stored in local or remote and create a JsonFile instance with it
     */
    protected static function getJsonComposer($path)
    {
        $rfs = null;

        if (preg_match('{^https?://}i', $path)) {
            // RemoteFilesystem constructor need an IO interface, so we give it a NullIO object based on this interface
            $config = new Config();
            $rfs = new RemoteFilesystem(new NullIO(), $config);
        }

        $composerFile = new JsonFile($path, $rfs);

        return $composerFile;
    }
}