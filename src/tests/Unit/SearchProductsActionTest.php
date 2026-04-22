<?php

namespace Tests\Unit\Modules\Product\Application\Actions;

use App\Modules\Product\Application\Actions\SearchProductsAction;
use App\Modules\Product\Application\DTO\ProductSearchDto;
use App\Modules\Product\Domain\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class SearchProductsActionTest extends MockeryTestCase
{
    public function test_it_delegates_search_to_repository(): void
    {
        $repository = Mockery::mock(ProductRepositoryInterface::class);
        $paginator = Mockery::mock(LengthAwarePaginator::class);

        $dto = new ProductSearchDto(
            q: 'test',
            priceFrom: null,
            priceTo: null,
            categoryId: null,
            inStock: null,
            ratingFrom: null,
            sort: 'newest',
            page: 1,
            perPage: 15
        );

        $repository->shouldReceive('search')
            ->once()
            ->with($dto)
            ->andReturn($paginator);

        $action = new SearchProductsAction($repository);
        $result = $action->execute($dto);

        $this->assertSame($paginator, $result);
    }
}
