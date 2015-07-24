<?php
namespace Designitgmbh\MonkeyAccess\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * AccessRights are defined by an action and a resource
 *
 * They can be applied to profiles, and therefore manage the access
 * of a user with a specific profile to a resource
 *
 * It is also possible to define aliases for actions and resources
 *
 * @author		Philipp Pajak
 */

class AccessRight extends Model {

	public function allowedProfileTypes() 
	{
		return $this->belongsToMany('Designitgmbh\MonkeyAccess\Models\ProfileType', 'profile_type_allowed_access_right');
	}

	public function allowedProfiles() 
	{
		return $this->belongsToMany('Designitgmbh\MonkeyAccess\Models\Profile', 'profile_allowed_access_right');
	}
}


?>