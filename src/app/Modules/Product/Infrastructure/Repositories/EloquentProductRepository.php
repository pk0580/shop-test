<?php

namespace App\Modules\Product\Infrastructure\Repositories;

use App\Modules\Product\Application\DTO\ProductSearchDto;
use App\Modules\Product\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Product\Infrastructure\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function search(ProductSearchDto $dto): LengthAwarePaginator
    {
        $query = Product::query()
            ->when($dto->q, fn($q) => $q->where('name', 'like', '%' . $dto->q . '%'))
            ->when($dto->priceFrom !== null, fn($q) => $q->where('price', '>=', $dto->priceFrom))
            ->when($dto->priceTo !== null, fn($q) => $q->where('price', '<=', $dto->priceTo))
            ->when($dto->categoryId !== null, fn($q) => $q->where('category_id', $dto->categoryId))
            ->when($dto->inStock !== null, fn($q) => $q->where('in_stock', $dto->inStock))
            ->when($dto->ratingFrom !== null, fn($q) => $q->where('rating', '>=', $dto->ratingFrom));

        $query = $this->applySort($query, $dto->sort);

        return $query->paginate(
            perPage: $dto->perPage,
            page: $dto->page
        );
    }

    private function applySort($query, ?string $sort)
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating_desc' => $query->orderBy('rating', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };
    }
}
