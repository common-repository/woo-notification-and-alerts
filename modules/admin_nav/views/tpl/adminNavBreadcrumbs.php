<?php
	$countBreadcrumbs = count($this->breadcrumbsList);
?>
<nav id="woobewoo-breadcrumbs" class="woobewoo-breadcrumbs <?php dispatcherWns::doAction('adminBreadcrumbsClassAdd')?>">
	<?php dispatcherWns::doAction('beforeAdminBreadcrumbs')?>
	<?php foreach($this->breadcrumbsList as $i => $crumb) { ?>
		<?php if($i < ($countBreadcrumbs - 1)) { ?>
			<a class="woobewoo-breadcrumb-el" href="<?php echo $crumb['url']?>"><?php echo $crumb['label']?></a>
			<i class="fa fa-angle-right" aria-hidden="true"></i>
		<?php } else {?>
			<span class="woobewoo-breadcrumb-el-active"><?php echo $crumb['label']?></span>
		<?php }?>
	<?php }?>
	<?php dispatcherWns::doAction('afterAdminBreadcrumbs')?>
</nav>
