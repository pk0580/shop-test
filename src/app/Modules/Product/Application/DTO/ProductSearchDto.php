<?php

namespace App\Modules\Product\Application\DTO;

class ProductSearchDto
{
    public function __construct(
        public readonly ?string $q = null,
        public readonly ?float $priceFrom = null,
        public readonly ?float $priceTo = null,
        public readonly ?int $categoryId = null,
        public readonly ?bool $inStock = null,
        public readonly ?float $ratingFrom = null,
        public readonly ?string $sort = 'newest',
        public readonly int $page = 1,
        public readonly int $perPage = 15,
    ) {}
}
