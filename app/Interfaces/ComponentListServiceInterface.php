<?php

namespace App\Interfaces;

use App\Dto\ComponentAddDto;
use App\Models\Component;

interface ComponentListServiceInterface
{
    public function createComponent(ComponentAddDto $dto): Component;
}
