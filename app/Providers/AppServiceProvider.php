<?php

namespace App\Providers;

use App\Actions\AtomicLocks;
use App\Actions\FetchAvailableStockById;
use App\Actions\FetchNumberOfRemaining;
use App\Actions\FetchNumberOfRemainingInStock;
use App\Actions\FetchProductByStockAndProductCode;
use App\Actions\ProductReservation;
use App\Actions\ProductsReservation;
use App\Actions\ProductsReservationInStock;
use App\Actions\ReleaseProductsReserve;
use App\Actions\ReleaseProductReserveByDTO;
use App\Actions\ReleaseProductsReserveInStock;
use App\Contracts\Actions\AtomicLocksContract;
use App\Contracts\Actions\FetchAvailableStockByIdContract;
use App\Contracts\Actions\FetchNumberOfRemainingContract;
use App\Contracts\Actions\FetchNumberOfRemainingInStockContract;
use App\Contracts\Actions\FetchProductByStockAndProductCodeContract;
use App\Contracts\Actions\ProductReservationContract;
use App\Contracts\Actions\ProductsReservationContract;
use App\Contracts\Actions\ProductsReservationInStockContract;
use App\Contracts\Actions\ReleaseProductsReserveContract;
use App\Contracts\Actions\ReleaseProductReserveByDTOContract;
use App\Contracts\Actions\ReleaseProductsReserveInStockContract;
use App\Contracts\Repository\ProductRepositoryContract;
use App\Contracts\Repository\StockRepositoryContract;
use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryContract::class,
            ProductRepository::class
        );

        $this->app->bind(
            StockRepositoryContract::class,
            StockRepository::class
        );

        $this->app->bind(
            FetchNumberOfRemainingInStockContract::class,
            FetchNumberOfRemainingInStock::class
        );

        $this->app->bind(
            FetchAvailableStockByIdContract::class,
            FetchAvailableStockById::class
        );

        $this->app->bind(
            FetchProductByStockAndProductCodeContract::class,
            FetchProductByStockAndProductCode::class
        );

        $this->app->bind(
            ProductReservationContract::class,
            ProductReservation::class
        );

        $this->app->bind(
            ReleaseProductsReserveInStockContract::class,
            ReleaseProductsReserveInStock::class
        );

        $this->app->bind(
            AtomicLocksContract::class,
            AtomicLocks::class
        );

        $this->app->bind(
            ReleaseProductReserveByDTOContract::class,
            ReleaseProductReserveByDTO::class
        );

        $this->app->bind(
            ReleaseProductsReserveContract::class,
            ReleaseProductsReserve::class
        );

        $this->app->bind(
            ProductsReservationInStockContract::class,
            ProductsReservationInStock::class
        );

        $this->app->bind(
            FetchNumberOfRemainingContract::class,
            FetchNumberOfRemaining::class
        );

        $this->app->bind(
            ProductsReservationContract::class,
            ProductsReservation::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
