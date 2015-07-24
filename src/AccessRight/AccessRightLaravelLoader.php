<?php

namespace Designitgmbh\MonkeyAccess\AccessRight;

/**
 * A Loader for the WAccessRight maintaining class
 * This loader is able to load Laravel/Eloquent style Access Rights
 *
 * It is using a Profile Structure in DB to set the Access Rights 
 * per profile and so per user.
 *
 * @author Philipp Pajak
 */

class AccessRightLaravelLoader {
	static function load($currentUser) {
		$accessRights = $currentUser->profile->accessRights;

		$loadingArray = [];
		foreach($accessRights as $accessRight) {
			$action = $accessRight->action;
			$resource = $accessRight->resource;
			$allowed = true; //if a constellation is given, it's allowed

			if(!isset($loadingArray[$resource]))
				$loadingArray[$resource] = [];

			$loadingArray[$resource][$action] = $allowed;			
		}

		AccessRight::init($loadingArray, false);
	}
}