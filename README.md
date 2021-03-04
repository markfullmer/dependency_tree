# Composer dependency tree visualization
This is a simple PHP library that will take a standard ``composer.json`` file and ``composer.lock`` file and generate a depedency tree, using [D3JS](https://d3js.org/).

Full demo at https://dependency.markfullmer.com

![Screenshot of dependency tree](dependencytree.png)

## Basic usage

1. Require this library to your PHP project:

```
composer require markfullmer/dependencytree
```

2. Use the library in a PHP file:

```php
use markfullmer\DependencyTree;
```

3. Copy the `d3.dependencyTree.js` file from this library into your project and load it into a web page.

```html
<script src='./js/d3.dependencyTree.js'></script>
```

3. Supply the contents of `composer.json` and `composer.lock` files as arguments and generate the tree.

```php
  $data = DependencyTree::generateTree($root, $lock, 0);
```

4. Render the resulting data via Javascript, supplying the data and an HTML target ID.

```php
  echo '
    <script>
      dependencyTree('. $data .');
    </script>
  ';
```
