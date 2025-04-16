<?php

namespace DumpsterfirePages\PageTemplate\PageWrapperComponent;

use DumpsterfirePages\PageTemplate\PageStructureComponents\PageWrapperComponent\PageWrapperComponent;

/**
 * @var PageWrapperComponent $this
 */

?>

<div class="page-wrapper-component">
    <?php foreach($this->getItems() as $item) {
        $item->render();
    } ?>
</div>