<?php


namespace EMedia\MultiTenant\Auth;


use Illuminate\Support\Facades\Config;

trait MultiTenantUserTrait
{

	public function tenants()
	{
		return $this->belongsToMany(Config::get('multiTenant.tenantModel'));
	}

	public function roles()
	{
		return $this->belongsToMany(Config::get('multiTenant.roleModel'));
	}

	public function hasFirstName()
	{
		return (empty($this->name))? false: true;
	}

}