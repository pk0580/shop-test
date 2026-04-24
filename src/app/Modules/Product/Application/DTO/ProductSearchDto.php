<?php

namespace App\Modules\Product\Application\DTO;

readonly class ProductSearchDto
{
    public function __construct(
        public ?string $q = null,
        public ?float  $priceFrom = null,
        public ?float  $priceTo = null,
        public ?int    $categoryId = null,
        public ?bool   $inStock = null,
        public ?float  $ratingFrom = null,
        public ?string $sort = 'newest',
        public int     $page = 1,
        public int     $perPage = 15,
    ) {}
}
