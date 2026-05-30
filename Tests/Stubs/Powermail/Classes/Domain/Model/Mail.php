<?php

declare(strict_types=1);

namespace In2code\Powermail\Domain\Model;

class Mail
{
    public function getForm(): Form
    {
        return new Form();
    }
}
