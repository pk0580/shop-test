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
        $query = Product::query();

        if ($dto->q) {
            $query->where('name', 'like', '%' . $dto->q . '%');
        }

        if ($dto->priceFrom !== null) {
            $query->where('price', '>=', $dto->priceFrom);
        }

        if ($dto->priceTo !== null) {
            $query->where('price', '<=', $dto->priceTo);
        }

        if ($dto->categoryId !== null) {
            $query->where('category_id', $dto->categoryId);
        }

        if ($dto->inStock !== null) {
            $query->where('in_stock', $dto->inStock);
        }

        if ($dto->ratingFrom !== null) {
            $query->where('rating', '>=', $dto->ratingFrom);
        }

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
