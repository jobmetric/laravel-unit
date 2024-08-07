<?php

namespace JobMetric\Unit;

use Illuminate\Support\Facades\Blade;
use JobMetric\PackageCore\Exceptions\MigrationFolderNotFoundException;
use JobMetric\PackageCore\Exceptions\RegisterClassTypeNotFoundException;
use JobMetric\PackageCore\PackageCore;
use JobMetric\PackageCore\PackageCoreServiceProvider;

class UnitServiceProvider extends PackageCoreServiceProvider
{
    /**
     * @param PackageCore $package
     *
     * @return void
     * @throws MigrationFolderNotFoundException
     * @throws RegisterClassTypeNotFoundException
     */
    public function configuration(PackageCore $package): void
    {
        $package->name('laravel-unit')
            ->hasConfig()
            ->hasMigration()
            ->hasTranslation()
            ->hasComponent()
            ->registerClass('Unit', Unit::class);
    }

    /**
     * After Boot Package
     *
     * @return void
     */
    public function afterBootPackage(): void
    {
        // add alias for components
        Blade::component('unit::components.unit', 'unit');
    }
}
