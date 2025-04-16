<?php

namespace DumpsterfireComponents\PageTemplate\DependenciesComponent;

use DumpsterfireComponents\PageTemplate\PageStructureComponents\DependenciesComponent\DependenciesComponent;

/**
 * @var DependenciesComponent $this
 */
?>

<?php foreach($this->getAssets() as $item) {
    echo "\n\t";
    $item->render();
} ?>
