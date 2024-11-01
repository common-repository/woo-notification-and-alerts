<?php
$isPro = $this->is_pro;
$labelPro = '';
if(!$isPro) {
	$adPath = $this->getModule()->getModPath().'img/ad/';
	$labelPro = ' Pro';
}

// $catArgs = array(
// 	'orderby' => 'name',
// 	'order' => 'asc',
// 	'hide_empty' => false,
// 	'parent' => 0
// );
//
// $productCategories = get_terms( 'product_cat', $catArgs );
// $categoryDisplay = array();
// foreach ($productCategories as $cat){
// 	$categoryDisplay[$cat->term_id] = $cat->name;
// }
//
// $tagArgs = array(
// 	'orderby' => 'name',
// 	'order' => 'asc',
// 	'hide_empty' => false,
// 	'parent' => 0
// );
//
// $productTags = get_terms( 'product_tag', $tagArgs );
// $tagsDisplay = array();
// foreach ($productTags as $tag){
// 	$tagsDisplay[$tag->term_id] = $tag->name;
// }
//
//
// $productAttr = wc_get_attribute_taxonomies();
//
// $attrDisplay = array(0 => __('Select...', WNS_LANG_CODE));
// foreach ($productAttr as $attr){
// 	$attrDisplay[$attr->attribute_id] = $attr->attribute_label;
// }
//
// $rolesMain = get_editable_roles();
// $roles = array();
//
// foreach($rolesMain as $key => $role){
// 	$roles[$key] = $role['name'];
// }

?>
<form id="wnsNotificationsEditForm" data-table-id="<?php echo $this->notificationSettings['id']; ?>" data-href="<?php echo $this->link;?>">
<aside class="col-md-3 woobewoo-sidebar">
	<!-- <div class="woobewoo-sidebar-breadcrumbs">
		<?php // echo $this->breadcrumbs; ?>
	</div> -->
	<div class="woobewoo-sidebar-navigation-wrapper">
		<div class="woobewoo-sidebar-module-navigation">
			<?php include_once 'woonotificationsPartNavEdit.php' ?>
		</div>
		<!-- <div class="woobewoo-sidebar-navigation">
			<?php // echo $this->navigation; ?>
		</div> -->
	</div>
</aside>
<div class="col-md-9 woobewoo-content">
	<div id="wnsNotificationsEditTabs">
		<section>
			<?php include_once 'woonotificationsPartContentEdit.php' ?>
		</section>
	</div>
</div>
<?php $title = isset($this->notificationSettings['title']) ? $this->notificationSettings['title'] : 'empty';?>
<?php echo htmlWns::text('title', array(
	'value' => $title,
	'attrs' => 'style="display:none;" id="wnsNotificationTitleMainTxt"',
	'required' => true,
)); ?>
<?php echo htmlWns::hidden( 'mod', array( 'value' => 'woonotifications' ) ) ?>
<?php echo htmlWns::hidden( 'action', array( 'value' => 'save' ) ) ?>
<?php echo htmlWns::hidden( 'id', array( 'value' => $this->notificationSettings['id'] ) ) ?>
</form>
