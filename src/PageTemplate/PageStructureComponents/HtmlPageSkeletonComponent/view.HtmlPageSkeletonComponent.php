<?php

namespace DumpsterfireComponents\PageTemplate\PageStructureComponents\HtmlPageSkeletonComponent;

/**
 * @var HtmlPageSkeletonComponent $this
 */

?>

<!DOCTYPE html>
<html lang="<?= $this->getLang() ?>">
    <?= $this->getHeadContent() ?>
    <body>
        <?= $this->getBodyContent() ?>
    </body>
</html>
