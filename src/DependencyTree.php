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

public static function generateTree($json_root, $json_lock, $recursion_level) {
  $lock = json_decode($json_lock, TRUE);
  $root = json_decode($json_root, TRUE);
  // Generate information for packages from the composer.lock file
  foreach ($lock['packages'] as $package) {
    $packages{$package['name']} = $package['require'] ?? [];
  }
  $tree = [];
  foreach ($root['require'] as $key => $constraint) {
    if (in_array($key, array_keys($packages))) {
      $children = self::getChildren($packages, $key, $recursion_level);
      $tree[] = [
        'name' => $key,
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

public static function getChildren($packages, $key, $recursion_level) {
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
    foreach (array_keys($packages[$key]) as $package) {
      if (!in_array($package, $skip)) {
        $children = self::getChildren($packages, $package, $recursion_level);
        $requirements[] = [
          'name' => $package,
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

}
