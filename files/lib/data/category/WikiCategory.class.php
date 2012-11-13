<?php
namespace wiki\data\category;

use wcf\data\category\ViewableCategory;

class WikiCategory extends ViewableCategory {
	/**
	 * Returns true if the category is accessible for the given user. If no
	 * user is given, the active user is used.
	 *
	 * @param    wcf\data\user\User        $user
	 * @return    boolean
	 */
	public function isAccessible() {
		return true;
	}
}
