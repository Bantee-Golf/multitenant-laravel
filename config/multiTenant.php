<?php

return [

	'defaultRoles'	=> [
		[
			'name'			=> 'owner',
			'display_name' 	=> 'Owner',
			'description'	=> 'Account Owner. Has all permissions.'
		],
		[
			'name'			=> 'member',
			'display_name' 	=> 'Members',
			'description'	=> 'Account Members'
		],
		[
			'name'			=> 'admin',
			'display_name' 	=> 'Admins',
			'description'	=> 'Account Admin. Can administer the account and Members.'
		]
	],

	'adminRoleNames'	=> ['admin', 'owner'],
	'defaultRoleNames'	=> ['admin', 'owner', 'member'],

	'roleModel'			=> App\Entities\Auth\Role::class,
	'permissionModel'	=> App\Entities\Auth\Permission::class,
	'tenantModel'		=> App\Entities\Auth\Tenant::class,

	'invitationsRepo'	=> EMedia\Oxygen\Entities\Invitations\InvitationRepository::class,

];