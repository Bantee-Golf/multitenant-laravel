<?php

namespace EMedia\MultiTenant\Entities;

use EMedia\MultiTenant\Exceptions\MultiTenancyNotActiveException;
use EMedia\MultiTenant\Exceptions\TenantInvalidIdException;
use EMedia\MultiTenant\Exceptions\TenantNotBoundException;
use EMedia\MultiTenant\Exceptions\TenantNotSetException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use ReflectionException;

class TenantManager
{

	private $tenant;
	private $enabled;
	private $active;

	public function __construct()
	{
		$this->enable();
		$this->active = config('auth.multiTenantActive');
	}

	/**
	 * Check if multi-tenancy is activated by user
	 *
	 * @throws MultiTenancyNotActiveException
	 */
	private function checkForActivation()
	{
		if (!$this->active) throw new MultiTenancyNotActiveException();
	}

	/**
	 * Set the Tenant for this Session with a valid Tenant ID
	 *
	 * @param $id
	 * @throws TenantInvalidIdException
	 * @throws TenantNotBoundException
	 */
	public function setTenantById($id)
	{
		$this->checkForActivation();
		try
		{
			$tenantResolver = app('emedia.tenantManager.tenant');
		}
		catch (ReflectionException $ex)
		{
			throw new TenantNotBoundException();
		}

		$tenant = $tenantResolver::find($id);
		if (empty($tenant) || empty($tenant->id))
			throw new TenantInvalidIdException();

		$this->setTenant($tenant);
	}

	/**
	 * Set the Tenant for this Session
	 *
	 * @param Model $tenant
	 */
	public function setTenant(Model $tenant)
	{
		$this->checkForActivation();
		$this->tenant = $tenant;
		Session::put('tenant_id', $tenant->id);
	}

	/**
	 * Get the current Tenant for this Session
	 *
	 * @return mixed
	 * @throws TenantInvalidIdException
	 * @throws TenantNotBoundException
	 * @throws TenantNotSetException
	 */
	public function getTenant()
	{
		if ( ! $this->isTenantSet() ) throw new TenantNotSetException();

		// if this is a new request, load session from storage
		if (!$this->tenantLoaded()) {
			$sessionTenantId = Session::get('tenant_id');
			$this->setTenantById($sessionTenantId);
		}

		return $this->tenant;
	}

	public function isTenantSet()
	{
		if (Session::has('tenant_id')) return true;

		return $this->tenantLoaded();
	}

	public function isTenantNotSet()
	{
		return !$this->isTenantSet();
	}

	protected function tenantLoaded()
	{
		return ($this->tenant == null || empty($this->tenant->id))? false: true;
	}

	public function clearTenant()
	{
		$this->tenant = null;
		Session::remove('tenant_id');
	}

	public function disable()
	{
		$this->enabled = false;
	}

	public function enable()
	{
		$this->enabled = true;
	}

	/**
	 * Check if the current instance is active or inactive.
	 * MultiTenancy must be 'active' for it to be enabled or disabled.
	 *
	 * @return boolean
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}

	/**
	 * Check if the tenant manager is active for the application
	 *
	 * @return boolean
	 */
	public function multiTenancyIsActive()
	{
		return $this->active;
	}

	/**
	 * Get all Tenants associated with the current User
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function allTenants()
	{
		$this->checkForActivation();
		$tenantRepo = app(config('auth.tenantRepository'));
		return $tenantRepo->all();
	}

}