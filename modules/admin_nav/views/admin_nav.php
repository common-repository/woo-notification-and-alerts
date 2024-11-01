<?php
class admin_navViewWns extends viewWns {
	public function getBreadcrumbs() {
		$this->assign('breadcrumbsList', dispatcherWns::applyFilters('mainBreadcrumbs', $this->getModule()->getBreadcrumbsList()));
		return parent::getContent('adminNavBreadcrumbs');
	}
}
