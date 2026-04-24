<?php

namespace App\Modules\Product\Application\Actions;

use App\Modules\Product\Application\DTO\ProductSearchDto;
use App\Modules\Product\Domain\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class SearchProductsAction
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(ProductSearchDto $dto): LengthAwarePaginator
    {
        return $this->repository->search($dto);
    }
}
