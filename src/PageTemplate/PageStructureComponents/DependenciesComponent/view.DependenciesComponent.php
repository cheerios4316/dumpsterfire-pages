<?php

namespace DumpsterfirePages\PageTemplate\DependenciesComponent;

use DumpsterfirePages\PageTemplate\PageStructureComponents\DependenciesComponent\DependenciesComponent;

/**
 * @var DependenciesComponent $this
 */
?>

<?php foreach($this->getAssets() as $item) {
    echo "\n\t";
    $item->render();
} ?>
