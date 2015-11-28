<?php

namespace EMedia\MultiTenant;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class MultiTenantServiceProvider extends ServiceProvider
{

	public function boot()
	{
		$this->publishes([
			__DIR__.'/../config/' => config_path(),
		], 'config');
	}

	public function register()
	{
		$this->app->bind('emedia.tenantManager.tenant', config('multiTenant.tenantModel'));

		$this->app->singleton('emedia.tenantManager', function () {
			return $this->app->make('EMedia\MultiTenant\Entities\TenantManager');
		});

	}

}