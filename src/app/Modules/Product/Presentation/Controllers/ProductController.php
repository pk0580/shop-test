<?php

namespace App\Modules\Product\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Application\Actions\SearchProductsAction;
use App\Modules\Product\Application\DTO\ProductSearchDto;
use App\Modules\Product\Presentation\Requests\SearchProductsRequest;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __invoke(SearchProductsRequest $request, SearchProductsAction $action): JsonResponse
    {
        $dto = new ProductSearchDto(
            q: $request->validated('q'),
            priceFrom: ($v = $request->validated('price_from')) !== null ? (float)$v : null,
            priceTo: ($v = $request->validated('price_to')) !== null ? (float)$v : null,
            categoryId: ($v = $request->validated('category_id')) !== null ? (int)$v : null,
            inStock: $request->has('in_stock') ? $request->boolean('in_stock') : null,
            ratingFrom: ($v = $request->validated('rating_from')) !== null ? (float)$v : null,
            sort: $request->validated('sort', 'newest'),
            page: (int)$request->validated('page', 1),
            perPage: (int)$request->validated('per_page', 15),
        );

        $products = $action->execute($dto);

        return response()->json($products);
    }
}
