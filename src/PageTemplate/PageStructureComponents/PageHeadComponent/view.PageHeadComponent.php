<?php

namespace DumpsterfirePages\PageTemplate\PageStructureComponents\PageHeadComponent;

/**
 * @var PageHeadComponent $this
 */

?>

<head>
    <title><?= $this->getTitle() ?></title>
    <?= $this->getMetaHtml() ?>
    <?php
        $this->getDependenciesComponent()->render();
        echo "\n";
    ?>
</head>
