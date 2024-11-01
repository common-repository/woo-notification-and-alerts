<?php $text_rules = !empty($this->settings['settings']['content']['text_rules']) ? $this->settings['settings']['content']['text_rules'] : array(); ?>

<?php foreach ($text_rules as $key => $text_rule) {?>

<?php if ($text_rule['id'] == 0) {?>
	<div class="wns-text_rule-tmp" style="display:none;">
<?php }?>

<div class="wns-tab-text_rule-container" data-text_rule="<?php echo $key ?>">

	<div class="row wns-tab-col-row-option">
		<div class="col-md-6">
			<input type="hidden" class="text_rule_id" name="settings[content][text_rules][<?php echo $key ?>][id]" readonly value="<?php echo $text_rule['id'] ?>" >
			<?php echo htmlWns::text('settings[content][text_rules]['.$key.'][name]', array(
				'value' => (isset($text_rule['name']) ? $text_rule['name'] : ''),
				'attrs' => 'class="text_rule_name" title="'. __('Click to copy text rule code', WNS_LANG_CODE) .'" readonly ',
			))?>
		</div>
		<div class="col-md-6">
			<div class="wns-text-rules-buttons">
				<button class="wns-text-rules-button wns-text-rules-button-duplicate" title="<?php echo __('Duplicate', WNS_LANG_CODE)?>"><i class="fa fa-files-o" aria-hidden="true"></i></button>
				<button class="wns-text-rules-button wns-text-rules-button-remove" title="<?php echo __('Delete', WNS_LANG_CODE)?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
			</div>
		</div>
	</div>

	<div class="row wns-tab-col-row-option">
		<div class="col-md-12">
			<?php echo htmlWns::selectbox('settings[content][text_rules]['.$key.'][type]', array(
				'options' => $this->textRulesTypes,
				'value' => (isset($text_rule['type']) ? $text_rule['type'] : ''),
				'attrs' => 'class="wns-shortcode-select"',
			))?>
		</div>
	</div>

	<div class="row wns-tab-col-row-option wns-text-rules-row" data-select="numbers" style="display:none">
		<div class="col-md-6">
			<?php echo htmlWns::selectbox('settings[content][text_rules]['.$key.'][numbers][select]', array(
				'options' => array('random' => __('Random', WNS_LANG_CODE), 'exact' => __('Exact', WNS_LANG_CODE)),
				'value' => (isset($text_rule['numbers']['select']) ? $text_rule['numbers']['select'] : ''),
				'attrs' => 'class="wns-shortcode-data-children"',
			))?>
		</div>
		<div class="col-md-3" data-parent="numbers" data-children="random">
			<?php echo htmlWns::text('settings[content][text_rules]['.$key.'][numbers][random][from]', array(
				'value' => (isset($text_rule['numbers']['random']['from']) ? $text_rule['numbers']['random']['from'] : ''),
				'attrs' => '',
				'placeholder' => __('From', WNS_LANG_CODE),
			))?>
		</div>
		<div class="col-md-3" data-parent="numbers" data-children="random">
			<?php echo htmlWns::text('settings[content][text_rules]['.$key.'][numbers][random][to]', array(
				'value' => (isset($text_rule['numbers']['random']['to']) ? $text_rule['numbers']['random']['to'] : ''),
				'attrs' => '',
				'placeholder' => __('To', WNS_LANG_CODE),
			))?>
		</div>
		<div class="col-md-6" data-parent="numbers" data-children="exact" style="display:none">
			<?php echo htmlWns::text('settings[content][text_rules]['.$key.'][numbers][exact]', array(
				'value' => (isset($text_rule['numbers']['exact']) ? $text_rule['numbers']['exact'] : ''),
				'attrs' => '',
				'placeholder' => __('Number', WNS_LANG_CODE),
			))?>
		</div>
	</div>

	<div class="row wns-tab-col-row-option wns-text-rules-row" data-select="text" style="display:none">
		<div class="col-md-12" data-parent="text" data-children="random">
			<p><?php echo __('You can use random text using the delimiter ~', WNS_LANG_CODE); ?></p>
		</div>
		<div class="col-md-6">
			<?php echo htmlWns::selectbox('settings[content][text_rules]['.$key.'][text][select]', array(
				'options' => array('random' => __('Random', WNS_LANG_CODE), 'one-by-one' => __('One-by-one', WNS_LANG_CODE)),
				'value' => (isset($text_rule['text']['select']) ? $text_rule['text']['select'] : ''),
				'attrs' => 'class="wns-shortcode-data-children"',
			))?>
		</div>
		<div class="col-md-6" data-parent="text" data-children="random">
			<?php echo htmlWns::text('settings[content][text_rules]['.$key.'][text][random]', array(
				'value' => (!empty($text_rule['text']['random']) ? $text_rule['text']['random'] : ''),
				'attrs' => '',
				'placeholder' => __('Type text', WNS_LANG_CODE),
			))?>
		</div>
		<div class="col-md-6" data-parent="text" data-children="one-by-one" style="display:none">
			<?php echo htmlWns::text('settings[content][text_rules]['.$key.'][text][one-by-one]', array(
				'value' => (!empty($text_rule['text']['one-by-one']) ? $text_rule['text']['one-by-one'] : ''),
				'attrs' => '',
				'placeholder' => __('Type text', WNS_LANG_CODE),
			))?>
		</div>
	</div>

	<div class="row wns-tab-col-row-option wns-text-rules-row" data-select="categories" style="display:none">
		<div class="col-md-6">
			<?php echo htmlWns::selectbox('settings[content][text_rules]['.$key.'][categories][select]', array(
				'options' => array('custom' => __('Custom', WNS_LANG_CODE), 'user' => __('User current category', WNS_LANG_CODE)),
				'value' => (isset($text_rule['categories']['select']) ? $text_rule['categories']['select'] : ''),
				'attrs' => 'class="wns-shortcode-data-children"',
			))?>
		</div>
		<div class="col-md-6" data-parent="categories" data-children="custom">
			<?php echo htmlWns::selectlist('settings[content][text_rules]['.$key.'][categories][custom]', array(
				'options' => $this->productCategories,
				'value' => (isset($text_rule['categories']['custom']) ? $text_rule['categories']['custom'] : ''),
				'attrs' => 'multiple class="wns-shortcode-select-multiple"',
			))?>
		</div>
	</div>

	<div class="row wns-tab-col-row-option wns-text-rules-row" data-select="tags" style="display:none">
		<div class="col-md-12">
			<?php echo htmlWns::selectlist('settings[content][text_rules]['.$key.'][tags]', array(
				'options' => $this->tagsList,
				'value' => (isset($text_rule['tags']) ? $text_rule['tags'] : ''),
				'attrs' => 'multiple class="wns-shortcode-select-multiple"',
			))?>
		</div>
	</div>

	<div class="row wns-tab-col-row-option wns-text-rules-row" data-select="product" style="display:none">
		<div class="col-md-12" data-parent="product" data-children="random">
			<!-- <p><?php echo __('If you want use all products in random roll let this field empty', WNS_LANG_CODE); ?></p> -->
		</div>
		<div class="col-md-6">
			<?php echo htmlWns::selectbox('settings[content][text_rules]['.$key.'][product][select]', array(
				'options' => array('random' => __('Random', WNS_LANG_CODE), 'one-by-one' => __('One by one', WNS_LANG_CODE)),
				'value' => (isset($text_rule['product']['select']) ? $text_rule['product']['select'] : ''),
				'attrs' => 'class="wns-shortcode-data-children"',
			))?>
		</div>
		<div class="col-md-6" data-parent="product" style="margin-bottom:10px;">
			<input type="text" class="wns-product-search" placeholder="<?php echo __('Select product title', WNS_LANG_CODE) ?>">
		</div>
		<div class="col-md-12" data-parent="product" data-children="random">
			<?php echo htmlWns::selectlist('settings[content][text_rules]['.$key.'][product][random]', array(
				'options' => (isset($text_rule['product']['random'])) ? frameWns::_()->getModule('woonotifications')->searchProductsByIDs($text_rule['product']['random']) : array(),
				'value' => (isset($text_rule['product']['random']) ? $text_rule['product']['random'] : ''),
				'attrs' => 'multiple class="wns-shortcode-select-multiple"',
			))?>
		</div>
		<div class="col-md-12" data-parent="product" data-children="one-by-one">
			<?php echo htmlWns::selectlist('settings[content][text_rules]['.$key.'][product][one-by-one]', array(
				'options' => (isset($text_rule['product']['one-by-one'])) ? frameWns::_()->getModule('woonotifications')->searchProductsByIDs($text_rule['product']['one-by-one']) : array(),
				'value' => (isset($text_rule['product']['one-by-one']) ? $text_rule['product']['one-by-one'] : ''),
				'attrs' => 'multiple class="wns-shortcode-select-multiple"',
			))?>
		</div>
	</div>

	<div class="row wns-tab-col-row-option wns-text-rules-row" data-select="location" style="display:none">
		<div class="col-md-12" data-parent="location" data-children="user_loc">
			<p><?php echo __('Show user city and country', WNS_LANG_CODE); ?></p>
		</div>
		<div class="col-md-12" data-parent="location" data-children="city_nearest">
			<p><?php echo __('Show user city or random nearest cities', WNS_LANG_CODE); ?></p>
		</div>
		<div class="col-md-12">
			<?php echo htmlWns::selectbox('settings[content][text_rules]['.$key.'][location]', array(
				'options' => array('user_loc' => __('User location: city, country', WNS_LANG_CODE), 'city_nearest' => __('User city or random nearest cities', WNS_LANG_CODE)),
				'value' => (isset($text_rule['location']) ? $text_rule['location'] : ''),
				'attrs' => 'class="wns-shortcode-data-children"',
			))?>
		</div>
	</div>

	<div class="row wns-tab-col-row-option wns-text-rules-row" data-select="time" style="display:none">
		<div class="col-md-6">
			<?php echo htmlWns::selectbox('settings[content][text_rules]['.$key.'][time][format]', array(
				'options' => $this->timeFormatList,
				'value' => (isset($text_rule['time']['format']) ? $text_rule['time']['format'] : ''),
				'attrs' => 'class="wns-shortcode-data-children"',
			))?>
		</div>
		<div class="col-md-6">
			<?php echo htmlWns::selectbox('settings[content][text_rules]['.$key.'][time][type]', array(
				'options' => array('current' => __('Display current time', WNS_LANG_CODE), 'countdown' => __('Display countdown', WNS_LANG_CODE)),
				'value' => (isset($text_rule['time']['type']) ? $text_rule['time']['type'] : ''),
				'attrs' => 'class="wns-shortcode-data-children"',
			))?>
		</div>
		<div class="col-md-12" style="margin-top:10px;" data-parent="time" data-children="countdown">
			<?php echo htmlWns::text('settings[content][text_rules]['.$key.'][time][countdown]', array(
				'value' => (!empty($text_rule['time']['countdown']) ? $text_rule['time']['countdown'] : ''),
				'attrs' => '',
				'placeholder' => __('Target time', WNS_LANG_CODE),
			))?>
		</div>
	</div>

	<div class="row wns-tab-col-row-option wns-text-rules-row" data-select="users" style="display:none">
		<div class="col-md-12" data-parent="users" data-children="random">
			<p><?php echo __('If you want use all users name in random roll let this field empty', WNS_LANG_CODE); ?></p>
		</div>
		<div class="col-md-6">
			<?php echo htmlWns::selectbox('settings[content][text_rules]['.$key.'][users][select]', array(
				'options' => array('current' => __('User name', WNS_LANG_CODE), 'random' => __('Random', WNS_LANG_CODE)),
				'value' => (isset($text_rule['users']['select']) ? $text_rule['users']['select'] : ''),
				'attrs' => 'class="wns-shortcode-data-children"',
			))?>
		</div>
		<div class="col-md-6" data-parent="users" data-children="random">
			<?php echo htmlWns::selectlist('settings[content][text_rules]['.$key.'][users][random]', array(
				'options' => $this->usersNameList,
				'value' => (isset($text_rule['users']['random']) ? $text_rule['users']['random'] : ''),
				'attrs' => 'multiple class="wns-shortcode-select-multiple"',
			))?>
		</div>
	</div>

</div>

<?php if ($text_rule['id'] == 0) {?>
	</div>
<?php }?>

<?php } ?>
