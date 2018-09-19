<?php

namespace App\Http\Modules\Account;

trait NavigationManager
{

	/**
	 * Get the corresponding navigation of the authenticated user
	 * 
	 * @returns String navigation link
	 */
	public function getCorrespondingNavigation()
	{
		$this->allowedOnAdminNavigationLayout()
			->allowedOnAssistantNavigationLayout()
			->allowedOnStaffNavigationLayout()
			->allowedOnClientNavigationLayout();

		return isset($this->navigationLayout) ? $this->navigationLayout : $this->defaultNavigationLayout();
	}

	/**
	 * Returns admin navigation link
	 * 
	 * @return 
	 */
	public function allowedOnAdminNavigationLayout()
	{
		if($this->isAdmin()) {
			$this->navigationLayout = 'layouts.partials.navigation.admin';
		}

		return $this;
	}

	/**
	 * Returns assistant navigation link
	 * 
	 * @return 
	 */
	public function allowedOnAssistantNavigationLayout()
	{
		if($this->isAssistant()) {
			$this->navigationLayout = 'layouts.partials.navigation.assistant';
		}

		return $this;
	}

	/**
	 * Returns staff navigation link
	 * 
	 * @return 
	 */
	public function allowedOnStaffNavigationLayout()
	{
		if($this->isStaffExcept([0, 1])) {
			$this->navigationLayout = 'layouts.partials.navigation.staff';
		}

		return $this;
	}

	/**
	 * Returns client navigation link
	 * 
	 * @return 
	 */
	public function allowedOnClientNavigationLayout()
	{
		if($this->isStudent() || $this->isFaculty()) {
			$this->navigationLayout = 'layouts.partials.navigation.client';
		}

		return $this;
	}

	/**
	 * Returns the default navigation link
	 * 
	 * @return 
	 */
	public function defaultNavigationLayout()
	{
		$this->navigationLayout = 'layouts.partials.navigation.client';

		return $this;
	}
}