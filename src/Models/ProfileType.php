<?php
namespace Designitgmbh\MonkeyAccess\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ProfileType model
 *
 * A ProfileType generally specifies which AccessRights we can assign
 * to a user with a specific profile (of this ProfileType)
 *
 * @author		Philipp Pajak
 */

class ProfileType extends Model {
    public function profile()
	{
		return $this->hasMany('Profile');
	}

	public function allowedAccessRights() 
	{
		return $this->belongsToMany('Designitgmbh\MonkeyAccess\Models\AccessRight', 'profile_type_allowed_access_right');
	}	
}


?>