<?php

require 'vendor/autoload.php';

use JsonSchema\Validator;
use markfullmer\DependencyTree;

include 'head.html';
$json_lock = file_get_contents('./data/composer.lock');
$json_root = file_get_contents('./data/composer.json');

if (isset($_POST['root']) && isset($_POST['lock'])) {
  $json_lock = $_POST['lock'];
  $json_root = $_POST['root'];
}
$checked = '';
$version = FALSE;
if (isset($_POST['version'])) {
  $checked = 'checked="checked"';
  $version = TRUE;
}
$expanded = '';
$expand = '';
if (isset($_POST['expanded'])) {
  $expanded = 'checked="checked"';
  $expand = 'true';
}

echo '
<div class="container">
  <form action="//' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '" method="POST">
    <div class="row">
      <div class="six columns">
        <label for="json">Paste <code>composer.json</code> here</label>
        <textarea id="json" class="u-full-width textbox" placeholder="Paste composer.json" name="json">' . $json_root . '</textarea>
      </div>
      <div class="six columns">
        <label for="lock">Paste <code>composer.lock</code> here</label>
        <textarea id="lock" class="u-full-width textbox" placeholder="Paste composer.json" name="lock">' . $json_lock . '</textarea>
      </div>
    </div>
    <div class="row">
      <div class="twelve columns">
        <label><input name="version" type="checkbox" ' .$checked . ' />Display version information</label>
        <label><input name="expanded" type="checkbox" ' .$expanded . ' />Begin with tree expanded</label>
        <input type="submit" name="submit" value="Generate tree" />
      </div>
    </div>
  </form>
  <figure id="tree"></figure>
</div>
<script> 
  var json = document.getElementById("json");
  var myCodeMirror = CodeMirror.fromTextArea(json, { "theme": "ambiance" });
  var lock = document.getElementById("lock");
  var myCodeMirror = CodeMirror.fromTextArea(lock, {"theme": "ambiance"});
</script>
';

$print = TRUE;
$validator = new Validator;
$data = json_decode($json_root);
$schema = (object) ['$ref' => 'https://getcomposer.org/schema.json'];
$validator->validate($data, $schema);
if (!$validator->isValid()) {
  $print = FALSE;
  foreach ($validator->getErrors() as $error) {
    echo '<pre>';
    printf("[%s] %s\n", $error['property'], $error['message']);
    echo '</pre>';
  }
}
json_decode($json_lock);
if (!json_last_error() == JSON_ERROR_NONE) {
  $print = FALSE;
}

if ($print) {
  $data = DependencyTree::generateTree($json_root, $json_lock, $version);
  echo '
    <script>
      dependencyTree('. $data .', "figure#tree", ' . $expand . ');
    </script>
  ';
}
else {
  echo '<h3>Invalid input</h3>';
}

?>
</body>
</html>
