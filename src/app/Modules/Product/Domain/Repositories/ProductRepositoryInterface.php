<?php

namespace App\Modules\Product\Domain\Repositories;

use App\Modules\Product\Application\DTO\ProductSearchDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function search(ProductSearchDto $dto): LengthAwarePaginator;
}
