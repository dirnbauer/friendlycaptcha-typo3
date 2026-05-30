<?php

declare(strict_types=1);

namespace In2code\Powermail\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Form
{
    /**
     * @return ObjectStorage<Page>
     */
    public function getPages(): ObjectStorage
    {
        /** @var ObjectStorage<Page> $pages */
        $pages = new ObjectStorage();
        return $pages;
    }
}
