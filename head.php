<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description"
    content="This is a simple PHP library that will take a standard composer.json file and composer.lock file and generate a dependency tree, using D3JS.">
  <meta property="og:title" content="Composer dependency tree, by Mark Fullmer">
  <meta property="og:image"
    content="https://dependency.markfullmer.com/complex-composer-dependency-tree.jpg">
  <meta property="og:description"
    content="This is a simple PHP library that will take a standard composer.json file and composer.lock file and generate a dependency tree, using D3JS." />
  <meta property="og:url" content="https://dependency.markfullmer.com/" />
  <meta property="og:site_name"
    content="Composer dependency tree, by Mark Fullmer" />
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:creator" content="@markfullmer">
  <meta name="twitter:title" content="Composer dependency tree generator">
  <meta name="twitter:description" content="This is a simple PHP library that will take a standard composer.json file and composer.lock file and generate a dependency tree, using D3JS.">
  <meta name="twitter:image" content="https://dependency.markfullmer.com/complex-composer-dependency-tree.jpg">
  <title>Composer dependency tree generator</title>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300&display=swap" rel="stylesheet">
  <style>
    <?php require './css/normalize.css'; ?><?php require './css/skeleton.css'; ?><?php require './css/custom.css'; ?><?php require './css/codemirror/lib/codemirror.css'; ?><?php require './css/codemirror/theme/ambiance.css'; ?>
  </style>
</head>

<body>
  <nav class="container">
    <a style="float:right" href="https://github.com/markfullmer/dependency_tree">Source code</a>
    <h1 class="heading">Composer dependency tree generator</h1>
  </nav>
  <script>
    <?php require './js/d3.v5.min.js'; ?>
    <?php require './js/d3.dependencyTree.js'; ?>
    <?php require './css/codemirror/lib/codemirror.js'; ?>
    <?php require './css/codemirror/mode/javascript/javascript.js'; ?>
  </script>
