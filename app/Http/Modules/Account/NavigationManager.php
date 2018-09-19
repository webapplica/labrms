<?php

namespace App\Http\Modules\Account;

trait NavigationManager
{

	/**
	 * Get the corresponding navigation of the authenticated user
	 * 
	 * @returns String navigation link
	 */
	protected function getCorrespondingNavigation()
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
	protected function allowedOnAdminNavigationLayout()
	{
		if($this->isAdmin()) {
			$this->navigationLayout = 'layouts.partials.navigation.admin';
		}
	}

	/**
	 * Returns assistant navigation link
	 * 
	 * @return 
	 */
	protected function allowedOnAssistantNavigationLayout()
	{
		if($this->isAssistant()) {
			$this->navigationLayout = 'layouts.partials.navigation.assistant';
		}
	}

	/**
	 * Returns staff navigation link
	 * 
	 * @return 
	 */
	protected function allowedOnStaffNavigationLayout()
	{
		if($this->isStaff()) {
			$this->navigationLayout = 'layouts.partials.navigation.staff';
		}
	}

	/**
	 * Returns client navigation link
	 * 
	 * @return 
	 */
	protected function allowedOnClientNavigationLayout()
	{
		if($this->isStudent() || $this->isFaculty()) {
			$this->navigationLayout = 'layouts.partials.navigation.client';
		}
	}

	/**
	 * Returns the default navigation link
	 * 
	 * @return 
	 */
	protected function defaultNavigationLayout()
	{
		$this->navigationLayout = 'layouts.partials.navigation.client';
	}
}