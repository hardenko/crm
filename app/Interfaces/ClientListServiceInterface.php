<?php

namespace App\Interfaces;

use App\Dto\GetClientListDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface ClientListServiceInterface
{
    /*
     * This service method takes dto and transfers to some Resource paginated in standard by 10pc/page
     */
    public function getClientList(GetClientListDto $dto): LengthAwarePaginator;
}
