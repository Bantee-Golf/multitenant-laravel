<?php

namespace EMedia\MultiTenant\Entities;

use EMedia\MultiTenant\Exceptions\TenantInvalidIdException;
use EMedia\MultiTenant\Exceptions\TenantNotBoundException;
use EMedia\MultiTenant\Exceptions\TenantNotSetException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use ReflectionException;

class TenantManager
{

	private $tenant;
	private $enabled;

	public function __construct()
	{
		$this->enable();
	}

	public function setTenantById($id)
	{
		try
		{
			$tenantResolver = App::make('emedia.tenantManager.tenant');
		}
		catch (ReflectionException $ex)
		{
			throw new TenantNotBoundException();
		}

		$tenant = $tenantResolver::find($id);
		if (empty($tenant) || empty($tenant->id))
			throw new TenantInvalidIdException();

		$this->tenant = $tenant;
	}

	public function setTenant(Model $tenant)
	{
		$this->tenant = $tenant;
	}

	public function getTenant()
	{
		if ($this->tenant == null || empty($this->tenant->id))
			throw new TenantNotSetException();

		return $this->tenant;
	}

	public function clearTenant()
	{
		$this->tenant = null;
	}

	public function disable()
	{
		$this->enabled = false;
	}

	public function enable()
	{
		$this->enabled = true;
	}

	public function isEnabled()
	{
		return $this->enabled;
	}

}