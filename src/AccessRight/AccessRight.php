<?php

namespace Designitgmbh\MonkeyAccess\AccessRight;

/**
 * Class managing the access rights for the current user	
 * Singleton, half-static
 * Singletone ensures that if we are the first ones to instantiate the object
 * no one can modify it and change the access rights later :)
 *
 * @author Philipp Pajak
 */

class AccessRight {
	static private $instance = null;
	private $accessRights = null;
	private $fallback = false;

	/**
     * Constructs the AccessRight class
     * Takes an array of access rights for the current user as a parameter
     * And provides a simple can/cannot interface to check the access rights
     *
     * The fallback parameter can furthermore define if we assume "ALLOW ALL"
     * or "DENY ALL" as standard.
     *
     * @param array $accessRights [$resource => [$action => true/false]]
     * @param boolean $fallback defines what to return if the access right is not specified
     * @return WAccessRight
     */
	private function __construct($accessRights, $fallback = false) {
		if(self::$instance !== null) {
			return false;
		}

		/* do constructor stuff */
		$this->accessRights = $accessRights;
		$this->fallback = $fallback;
	}

	/**
     * Returns an instance of the initialized AccessRight Object
     *
     * @return WAccessRight
     */
	private static function getInstance() {
		if (null === self::$instance) {
			//cannot get instance without creating it first
			//this is not a classic singleton
			//as creation is always done by the base controller
			return null;
		}
		return self::$instance;
	}


	/**
     * See the constructor for more information
     *
     * @param array $accessRights [$resource => [$action => true/false]]
     * @param boolean $fallback defines what to return if the access right is not specified
     * @return void
     */
	public static function init($accessRights, $fallback = false) {
		if(null === self::$instance) {
			//ok, we can init, because we are the first ones
			//just ensuring no one can change access rights later
			self::$instance = new AccessRight($accessRights);
		}
	}

	/**
     * Returns true if the current access right definition allows the current user
     * to perform $action on $resource
     *
     * @param string $action The action to be performed
     * @param string $resource The resource to be involved
     * @return boolean
     */
	public static function can($action, $resource) {
		if(($instance = self::getInstance()) === null)
			return false;

		foreach(self::aliasForResource($resource) as $resourceAlias) {
			if(self::hasAccessRight($action, $resourceAlias))
				return true;	
		}		

		if(self::hasAccessRight(self::aliasForAction($action), $resource))
			return true;

		return self::hasAccessRight($action, $resource);		
	}

	/**
     * Returns true if the current access right definition *denies* the current user
     * to perform $action on $resource
     *
     * @param string $action The action to be performed
     * @param string $resource The resource to be involved
     * @return boolean
     */
	public static function cannot($action, $resource) {
		return !self::can($action, $resource);
	}

	/**
	 * Returns the the actual saved access right for action and resource
	 * This does not handle aliases, it just checks for saved access rights
	 *
	 * @param string $action The action to be performed
     * @param string $resource The resource to be involved
     * @return boolean
	 */
	private static function hasAccessRight($action, $resource) {
		if(($instance = self::getInstance()) === null)
			return false;

		$accessRights = $instance->accessRights;

		if(isset($accessRights[$resource])) {
			if(isset($accessRights[$resource][$action])) {
				return $accessRights[$resource][$action];
			}
		}

		return $instance->fallback;
	}


	/**
	 * Returns the alias for an action, or the action itself if there is no known alias
	 * 
	 * @param string $action The action
	 * @return string
	 */
	private static function aliasForAction($action) {
		switch($action) {
			case "create":
			case "update":
			case "delete":
				return "manage";
				break;
			default:
				return $action;
		}
	}

	/**
	 * Returns the "all" and "x.all" alias for a resource
	 * To be used when a profile has all the rights.
	 *
	 * @return string
	 */
	private static function aliasForResource($resource) {
		$firstDotPos = strpos($resource, ".");

		if($firstDotPos !== false) {
			$shortResource = substr($resource, 0, $firstDotPos);
		}

		$lastDotPos = strrpos($resource, ".");

		if($lastDotPos !== false) {
			$longResource = substr($resource, 0, $lastDotPos);	
		}

		return [
			isset($shortResource) ? $shortResource.".all" : null,
			isset($longResource)  ? $longResource.".all" : null,
			$resource.".all", 
			"all"
		];
	}

}