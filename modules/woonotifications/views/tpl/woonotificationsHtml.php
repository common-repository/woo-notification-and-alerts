<?php $id = $this->divId; ?>
<?php $templateTitle = $this->templateTitle; ?>
<?php $settings = $this->settings; ?>
<?php $popupPosition = isset($settings['view_styles_position']) ? $settings['view_styles_position'] : 'right_bottom'; ?>

	<style>
		#wnsNotificationPopup_<?php echo $id ?> {
			<?php if ($this->isPreview) { ?>
				display:block !important;
			<?php }?>
			<?php if ( !empty($settings['view_styles_enable_background']) ) { ?>
				background: <?php echo $settings['view_styles_background_color'] ?>;
			<?php } ?>
			<?php if ( !empty($settings['view_styles_enable_borders']) ) { ?>
				border:<?php echo ( $settings['view_styles_border_width'] . ' ' . $settings['view_styles_border_style'] . ' ' . $settings['view_styles_border_color'] ) ?>;
				border-radius:<?php echo $settings['view_styles_border_radius'] ?>;
			<?php } ?>
			<?php if ( isset($settings['view_styles_padding_left']) ) { ?>
				margin-left: <?php echo $settings['view_styles_padding_left'] ?>;
				margin-right: <?php echo $settings['view_styles_padding_right'] ?>;
				margin-top: <?php echo $settings['view_styles_padding_top'] ?>;
				margin-bottom: <?php echo $settings['view_styles_padding_bottom'] ?>;
			<?php } ?>
		}
		#wnsNotificationPopup_<?php echo $id ?> .wnsTemplateTitle {
			<?php if ( !empty($settings['view_styles_enable_title_font']) ) { ?>
				font: <?php echo ( $settings['view_styles_title_font_weight'] . ' ' . $settings['view_styles_title_font_style'] . ' ' . $settings['view_styles_title_font_size'] . ' ' . $settings['view_styles_title_font_family'] ) ?>;
			<?php } ?>
		}
		#wnsNotificationPopup_<?php echo $id ?> .wnsTemplateContent {
			<?php if ( !empty($settings['view_styles_enable_description_font']) ) { ?>
				font: <?php echo ( $settings['view_styles_description_font_weight'] . ' ' . $settings['view_styles_description_font_style'] . ' ' . $settings['view_styles_description_font_size'] . ' ' . $settings['view_styles_description_font_family'] ) ?>;
			<?php } ?>
		}
		#wnsNotificationPopup_<?php echo $id ?> .wnsTemplateCloseButton {
			<?php if ( !empty($settings['view_styles_enable_close_button']) ) { ?>
				background: <?php echo $settings['view_styles_close_button_background'] ?>;
				border:<?php echo ( $settings['view_styles_close_button_border_width'] . ' ' . $settings['view_styles_close_button_border_style'] . ' ' . $settings['view_styles_close_button_border_color'] ) ?>;
				border-radius: <?php echo $settings['view_styles_close_button_border_radius'] ?>;
				color: <?php echo $settings['view_styles_close_button_icon_color'] ?>;
			<?php } ?>
		}
		#wnsNotificationPopup_<?php echo $id ?> .wnsTemplateCloseButton:hover {
			<?php if ( !empty($settings['view_styles_enable_close_button']) ) { ?>
				background: <?php echo $settings['view_styles_close_button_background_hover'] ?>;
			<?php } ?>
		}
		<?php if (!empty($settings['custom_css'])) {?>
				<?php 
					//echo $settings['custom_css'];
					$customCss = trim(preg_replace('/\/\*.*\*\//Us', '', $settings['custom_css']));
					$elements = explode('}', $customCss);
					$addIdent = '#wnsNotificationPopup_' . $id . ' ';
					$newCss = '';

					foreach ($elements as $element) {
						$pos = strpos($element, '{');
						if ($pos) {
							$name = substr($element, 0, $pos);
							if (strpos($name, '@') === false) {
								$element = $addIdent . $element;
							} else {
								$mediaName = $name . '{';
								$rule = substr($element, $pos + 1);
								if (strpos($rule, '{')) {
									$element = $mediaName . $addIdent . $rule;
								}
							}							
						}
						$newCss .= $element . '}';
					}
					echo substr($newCss, 0, -1);
				 ?>
		<?php }?>
	</style>

	<?php if ( isset($settings['view_styles_enable_overlay']) ) { ?>
		<style>
			#wnsNotificationPopup_<?php echo $id ?>_overlay {
				background: <?php echo $settings['view_styles_overlay_background_color'] ?>;
			}
		</style>
		<div class="wnsNotificationPopup_overlay wns_<?php echo $templateTitle?>_overlay" id="wnsNotificationPopup_<?php echo $id?>_overlay" style="display:none;"></div>
	<?php } ?>

	<div data-settings="<?php echo $this->dataSettings?>" class="wnsNotificationPopup <?php echo $popupPosition ?> wns_<?php echo $templateTitle?>" id="wnsNotificationPopup_<?php echo $id?>" style="display:none">
		<?php echo $this->template; ?>
	</div>
