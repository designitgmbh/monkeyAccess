<?php
namespace Designitgmbh\MonkeyAccess\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model {
	public function toArray()
	{
		$this->mySettings->toArray();
		return parent::toArray();
	}

    public function users()
	{
		return $this->hasMany('User');
	}

    public function profileType() {
    	return $this->belongsTo('Designitgmbh\MonkeyAccess\Models\ProfileType');
    }

	public function accessRights()
    {
    	return $this->belongsToMany('Designitgmbh\MonkeyAccess\Models\AccessRight', 'profile_allowed_access_right');
    }

}