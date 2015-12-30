<?php

namespace EMedia\MultiTenant\Auth;

trait MultiTenantUserTrait
{

	public function tenants()
	{
		return $this->belongsToMany(config('auth.tenantModel'));
	}

	public function roles()
	{
		return $this->belongsToMany(config('auth.roleModel'));
	}

}