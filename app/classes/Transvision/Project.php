<?php
namespace Transvision;

/**
 * Project class
 *
 * This is data used across the project to remove them from global scope and
 * make this data accessible from other classes.
 *
 * @package Transvision
 */
class Project
{
    /**
     * This array stores all the repositories we support in Transvision
     */
    public static $repositories = [
        'release'     => 'Release',
        'beta'        => 'Beta',
        'aurora'      => 'Aurora',
        'central'     => 'Central',
        'gaia'        => 'Gaia master',
        'gaia_1_2'    => 'Gaia 1.2',
        'gaia_1_3'    => 'Gaia 1.3',
        'gaia_1_4'    => 'Gaia 1.4',
        'mozilla_org' => 'mozilla.org',
    ];

    /**
     * Get the list of repositories.
     *
     * @return array list of local repositories
     */
    public static function getRepositories()
    {
        return array_keys(self::$repositories);
    }

    /**
     * Get the list of repositories with their Display name.
     * The array has repo folder names as keys and Display names as value:
     * ex: ['gaia_1_4' => 'Gaia 1.4', 'mozilla_org' => 'mozilla.org']
     *
     * @return array list of local repositories and their Display names
     */
    public static function getRepositoriesNames()
    {
        return self::$repositories;
    }

    /**
     * Get the list of repositories for Gaia.
     * The list is sorted by age (latest master -> older branch)
     *
     * @return array list of local repositories for Gaia
     */
    public static function getGaiaRepositories()
    {
        $gaia_repos = array_filter(
            self::getRepositories(),
            function($value) {
                if (Strings::startsWith($value, 'gaia_')) {
                    return $value;
                }
            }
        );

        // Sort repos from latest branch to oldest branch
        rsort($gaia_repos);
        // gaia repo is the latest master branch, always first
        array_unshift($gaia_repos, 'gaia');

        return $gaia_repos;
    }

    /**
     * Get the list of repositories for Desktop Applications
     *
     * @return array list of local repositories folder names
     */
    public static function getDesktopRepositories()
    {
        return array_diff(
            array_diff(self::getRepositories(), ['mozilla_org']),
            self::getGaiaRepositories()
        );
    }

    /**
     * Get the list of locales available for a repository
     *
     * @param  string $repository Name of the folder for the repository
     * @return array  A sorted list of locales
     */
    public static function getRepositoryLocales($repository)
    {
        $locales = Files::getFilenamesInFolder(TMX . $repository . '/', ['ab-CD']);
        return array_values($locales);
    }

    /**
     * Return the reference locale for a repository
     *
     * @param  string $repository Name of the folder for the repository
     * @return string Name of the reference locale
     */
    public static function getReferenceLocale($repository)
    {
        return $repository == 'mozilla_org' ? 'en-GB' : 'en-US';
    }

    /**
     * Check if the specified repository is supported
     *
     * @param  string $repository Name of the folder for the repository
     * @return boolean True if supported repository, False if unknown
     */
    public static function isValidRepository($repository)
    {
        return in_array($repository, self::getRepositories());
    }
}
