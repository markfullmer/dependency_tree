<?php

namespace markfullmer;

/**
 * Class DependencyTree.
 *
 * Given a valid composer.json & composer.lock, generate an array that can be used by D3JS.
 *
 * @author markfullmer <mfullmer@gmail.com>
 *
 * @link https://github.com/writecrow/tag-converter/
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class DependencyTree {

  /**
   * Main generation method.
   *
   * @param string $json_root
   *    The composer.json file as a string.
   * @param string $json_lock
   *    The composer.lock file as a string.
   * @param bool $version
   *    Whether or not to print version information.
   * 
   * @return array
   *    The nested array of dependencies.
   */
  public static function generateTree($json_root, $json_lock, $version) {
    $recursion_level = 0;
    $lock = json_decode($json_lock, TRUE);
    $root = json_decode($json_root, TRUE);
    $packages = self::parseLockFile($lock);
    $tree = [];
    foreach ($root['require'] as $key => $constraint) {
      if (in_array($key, array_keys($packages))) {
        $children = self::getChildren($packages, $key, $recursion_level, $version);
        $name = $key;
        if ($version) {
          $name .= ' ' . $constraint;
        }
        $tree[] = [
          'name' => $name,
          'children' => $children['requirements'],
        ];
      }
    }
    $output = [
      'name' => 'composer.json',
      'children' => $tree,
    ];
    return json_encode($output);
  }

  /**
   * Recursive function to create a tree of dependencies.
   *
   * @param array $packages
   *    An array of packages, keyed by name, including requirements & version.
   * @param [type] $key
   *    The name of a specific package.
   * @param [type] $recursion_level
   *    Internal marker to prevent memory errors due to recursion loops.
   * @param bool $version
   *    Whether or not the version number should be printed.
   * 
   * @return array
   *    A tree, consisting of the specified packages their children.
   */
  public static function getChildren($packages, $key, $recursion_level, $version) {
    $requirements = [];
    if ($recursion_level > 4) {
      return [
        'recursion_level' => $recursion_level,
        'requirements' => [],
      ];
    }
    $recursion_level++;
    $skip = ['drupal/core', 'php', 'composer/installers'];
    if (in_array($key, array_keys($packages))) {
      foreach (array_keys($packages[$key]['require']) as $package) {
        if (!in_array($package, $skip)) {
          $children = self::getChildren($packages, $package, $recursion_level, $version);
          $name = $package;
          if ($version && isset($packages[$package]['version'])) {
            // Some packages in a composer.lock file will not provide version numbers.
            $name .= ' ' . $packages[$package]['version'];
          }
          $requirements[] = [
            'name' => $name,
            'children' => $children['requirements'],
          ];
        }
      }
    }
    return [
      'recursion_level' => $recursion_level,
      'requirements' => $requirements,
    ];
  }

  /**
   * Derive desired information from composer.lock file.
   *
   * @param array $lock
   *    The composer.lock file, converted to a PHP array.
   * 
   * @return void
   *    An array of packages, keyed by name, including requirements & version.
   */
  public static function parseLockFile($lock) {
    // Generate information for packages from the composer.lock file.
    foreach ($lock['packages'] as $package) {
      $packages{$package['name']} = [
        'require' => $package['require'] ?? [],
        'version' => $package['version']
      ];
    }
    return $packages;
  }

}
