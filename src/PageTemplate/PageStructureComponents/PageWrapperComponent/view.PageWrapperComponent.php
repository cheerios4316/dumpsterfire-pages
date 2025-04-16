<?php

namespace DumpsterfireComponents\PageTemplate\PageWrapperComponent;

use DumpsterfireComponents\PageTemplate\PageStructureComponents\PageWrapperComponent\PageWrapperComponent;

/**
 * @var PageWrapperComponent $this
 */

?>

<div class="page-wrapper-component">
    <?php foreach($this->getItems() as $item) {
        $item->render();
    } ?>
</div>