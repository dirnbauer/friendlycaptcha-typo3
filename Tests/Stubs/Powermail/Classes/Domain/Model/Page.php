<?php

declare(strict_types=1);

namespace In2code\Powermail\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Page
{
    /**
     * @return ObjectStorage<Field>
     */
    public function getFields(): ObjectStorage
    {
        /** @var ObjectStorage<Field> $fields */
        $fields = new ObjectStorage();
        return $fields;
    }
}
