<?php

namespace EMedia\MultiTenant\Facades;


use Illuminate\Support\Facades\Facade;

class TenantManager extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'emedia.tenantManager';
	}
}