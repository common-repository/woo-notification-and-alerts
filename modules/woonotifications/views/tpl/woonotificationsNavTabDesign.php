<a href="#" class="wns-link" data-children="wns-tab-design" data-parent="none"><span class="woobewoo-tab-label"><?php echo __('Design', WNS_LANG_CODE); ?></span></a>
<div class="wns-main-nav-children wns-tab-design">
	<a href="#" class="wns-link" data-action="stay" data-children="wns-tab-design-templates" data-parent="wns-tab-design"><span class="woobewoo-tab-label"><?php echo __('Templates', WNS_LANG_CODE); ?></span></a>
	<div class="wns-main-nav-children wns-tab-design-templates">
			<?php require_once 'woonotificationsNavTabDesignTemplates.php' ?>
	</div>
	<a href="#" class="wns-link" data-children="wns-tab-design-view-styles" data-parent="wns-tab-design"><span class="woobewoo-tab-label"><?php echo __('View Styles', WNS_LANG_CODE); ?></span></a>
	<div class="wns-main-nav-children wns-tab-design-view-styles">
			<a href="#" class="wns-link" data-action="stay" data-children="wns-tab-design-view-styles-position" data-parent="wns-tab-design-view-styles"><span class="woobewoo-tab-label"><?php echo __('Position', WNS_LANG_CODE); ?></span></a>
			<div class="wns-main-nav-children wns-tab-design-view-styles-position">
				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Choose popup position', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::selectbox('settings[view_styles_position]', array(
							'options' => array(
								'left_bottom' => 'Left Bottom',
								'center_bottom' => 'Center Bottom',
								'right_bottom' => 'Right Bottom',
								'left_middle' => 'Left Middle',
								'center_middle' => 'Center Middle',
								'right_middle' => 'Right Middle',
								'left_top' => 'Left Top',
								'center_top' => 'Center Top',
								'right_top' => 'Right Top',
							),
							'value' => (isset($this->settings['settings']['view_styles_position']) ? $this->settings['settings']['view_styles_position'] : 'right_bottom'),
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Select left padding', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::text('settings[view_styles_padding_left]', array(
							'value' => isset($this->settings['settings']['view_styles_padding_left']) ? $this->settings['settings']['view_styles_padding_left'] : '15px',
							'attrs' => 'placeholder="15px" class=""'));
						?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Select right padding', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::text('settings[view_styles_padding_right]', array(
							'value' => isset($this->settings['settings']['view_styles_padding_right']) ? $this->settings['settings']['view_styles_padding_right'] : '15px',
							'attrs' => 'placeholder="15px" class=""'));
						?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Select top padding', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::text('settings[view_styles_padding_top]', array(
							'value' => isset($this->settings['settings']['view_styles_padding_top']) ? $this->settings['settings']['view_styles_padding_top'] : '15px',
							'attrs' => 'placeholder="15px" class=""'));
						?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Select bottom padding', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::text('settings[view_styles_padding_bottom]', array(
							'value' => isset($this->settings['settings']['view_styles_padding_bottom']) ? $this->settings['settings']['view_styles_padding_bottom'] : '15px',
							'attrs' => 'placeholder="15px" class=""'));
						?>
					</div>
				</div>
			</div>
			<a href="#" class="wns-link" data-action="stay" data-children="wns-tab-design-view-styles-background-overlay" data-parent="wns-tab-design-view-styles"><span class="woobewoo-tab-label"><?php echo __('Background and Overlay', WNS_LANG_CODE); ?></span></a>
			<div class="wns-main-nav-children wns-tab-design-view-styles-background-overlay">

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Override template background', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::checkbox('settings[view_styles_enable_background]', array(
							'checked' => (isset($this->settings['settings']['view_styles_enable_background']) ? (int) $this->settings['settings']['view_styles_enable_background'] : '')
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option wnsLoader wnsColorObserver">
					<div class="col-md-6">
						<?php _e('Background color and opacity', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::colorpicker('settings[view_styles_background_color]', array(
							'value' => (isset($this->settings['settings']['view_styles_background_color']) ? $this->settings['settings']['view_styles_background_color'] : 'rgba(255,255,255,1)'),
							'attrs' => 'style="width: 50px"',
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Enable overlay', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::checkbox('settings[view_styles_enable_overlay]', array(
							'checked' => (isset($this->settings['settings']['view_styles_enable_overlay']) ? (int) $this->settings['settings']['view_styles_enable_overlay'] : '')
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option wnsLoader wnsColorObserver">
					<div class="col-md-6">
						<?php _e('Overlay background color and opacity', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::colorpicker('settings[view_styles_overlay_background_color]', array(
							'value' => (isset($this->settings['settings']['view_styles_overlay_background_color']) ? $this->settings['settings']['view_styles_overlay_background_color'] : 'rgba(0,0,0,.3)'),
							'attrs' => 'style="width: 50px"',
						))?>
					</div>
				</div>
			</div>
			<a href="#" class="wns-link" data-action="stay" data-children="wns-tab-design-view-styles-borders" data-parent="wns-tab-design-view-styles"><span class="woobewoo-tab-label"><?php echo __('Borders', WNS_LANG_CODE); ?></span></a>
			<div class="wns-main-nav-children wns-tab-design-view-styles-borders">
				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Override template borders', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::checkbox('settings[view_styles_enable_borders]', array(
							'checked' => (isset($this->settings['settings']['view_styles_enable_borders']) ? (int) $this->settings['settings']['view_styles_enable_borders'] : '')
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Borders width', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::text('settings[view_styles_border_width]', array(
							'value' => isset($this->settings['settings']['view_styles_border_width']) ? $this->settings['settings']['view_styles_border_width'] : '1px',
							'attrs' => 'placeholder="1px" class=""'));
						?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option  wnsLoader wnsColorObserver">
					<div class="col-md-6">
						<?php _e('Borders color and opacity', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::colorpicker('settings[view_styles_border_color]', array(
							'value' => (isset($this->settings['settings']['view_styles_border_color']) ? $this->settings['settings']['view_styles_border_color'] : 'rgba(33,33,33,.7)'),
							'attrs' => 'style="width: 50px"',
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Borders style', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::selectbox('settings[view_styles_border_style]', array(
							'options' => array(
								'none' => 'none',
								'solid' => 'solid',
								'dotted' => 'dotted',
								'dashed' => 'dashed',
								'double' => 'double',
								'groove' => 'groove',
								'ridge' => 'ridge',
								'inset' => 'inset',
								'outset' => 'outset',
							),
							'value' => (isset($this->settings['settings']['view_styles_border_style']) ? $this->settings['settings']['view_styles_border_style'] : 'solid'),
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Borders radius', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::text('settings[view_styles_border_radius]', array(
							'value' => isset($this->settings['settings']['view_styles_border_radius']) ? $this->settings['settings']['view_styles_border_radius'] : '4px',
							'attrs' => 'placeholder="4px" class=""'));
						?>
					</div>
				</div>
			</div>
			<a href="#" class="wns-link" data-action="stay" data-children="wns-tab-design-view-styles-text" data-parent="wns-tab-design-view-styles"><span class="woobewoo-tab-label"><?php echo __('Text', WNS_LANG_CODE); ?></span></a>
			<div class="wns-main-nav-children wns-tab-design-view-styles-text">

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Override template title font', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::checkbox('settings[view_styles_enable_title_font]', array(
							'checked' => (isset($this->settings['settings']['view_styles_enable_title_font']) ? (int) $this->settings['settings']['view_styles_enable_title_font'] : '')
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Title font size', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::text('settings[view_styles_title_font_size]', array(
							'value' => isset($this->settings['settings']['view_styles_title_font_size']) ? $this->settings['settings']['view_styles_title_font_size'] : '16px',
							'attrs' => 'placeholder="16px" class=""'));
						?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Title font family', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::selectbox('settings[view_styles_title_font_family]', array(
							'options' => array(
								'arial' => 'arial',
								'sans-serif' => 'sans-serif',
								'tahoma' => 'tahoma',
							),
							'value' => (isset($this->settings['settings']['view_styles_title_font_family']) ? $this->settings['settings']['view_styles_title_font_family'] : 'arial'),
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Title font style', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::selectbox('settings[view_styles_title_font_style]', array(
							'options' => array(
								'normal' => 'normal',
								'italic' => 'italic',
								'oblique' => 'oblique',
							),
							'value' => (isset($this->settings['settings']['view_styles_title_font_style']) ? $this->settings['settings']['view_styles_title_font_style'] : 'normal'),
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Title font weight', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::selectbox('settings[view_styles_title_font_weight]', array(
							'options' => array(
								'bold' => 'bold',
								'bolder' => 'bolder',
								'lighter' => 'lighter',
								'normal' => 'normal',
								'100' => '100',
								'200' => '200',
								'300' => '300',
								'400' => '400',
								'500' => '500',
								'600' => '600',
								'700' => '700',
								'800' => '800',
								'900' => '900',
							),
							'value' => (isset($this->settings['settings']['view_styles_title_font_weight']) ? $this->settings['settings']['view_styles_title_font_weight'] : 'bold'),
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Override template description font', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::checkbox('settings[view_styles_enable_description_font]', array(
							'checked' => (isset($this->settings['settings']['view_styles_enable_description_font']) ? (int) $this->settings['settings']['view_styles_enable_description_font'] : '')
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Description font size', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::text('settings[view_styles_description_font_size]', array(
							'value' => isset($this->settings['settings']['view_styles_description_font_size']) ? $this->settings['settings']['view_styles_description_font_size'] : '14px',
							'attrs' => 'placeholder="14px" class=""'));
						?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Description font family', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::selectbox('settings[view_styles_description_font_family]', array(
							'options' => array(
								'arial' => 'arial',
								'sans-serif' => 'sans-serif',
								'tahoma' => 'tahoma',
							),
							'value' => (isset($this->settings['settings']['view_styles_description_font_family']) ? $this->settings['settings']['view_styles_description_font_family'] : 'arial'),
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Description font style', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::selectbox('settings[view_styles_description_font_style]', array(
							'options' => array(
								'normal' => 'normal',
								'italic' => 'italic',
								'oblique' => 'oblique',
							),
							'value' => (isset($this->settings['settings']['view_styles_description_font_style']) ? $this->settings['settings']['view_styles_description_font_style'] : 'normal'),
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Description font weight', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::selectbox('settings[view_styles_description_font_weight]', array(
							'options' => array(
								'bold' => 'bold',
								'bolder' => 'bolder',
								'lighter' => 'lighter',
								'normal' => 'normal',
								'100' => '100',
								'200' => '200',
								'300' => '300',
								'400' => '400',
								'500' => '500',
								'600' => '600',
								'700' => '700',
								'800' => '800',
								'900' => '900',
							),
							'value' => (isset($this->settings['settings']['view_styles_description_font_weight']) ? $this->settings['settings']['view_styles_description_font_weight'] : 'normal'),
						))?>
					</div>
				</div>
			</div>
			<a href="#" class="wns-link" data-action="stay" data-children="wns-tab-design-view-styles-close-button" data-parent="wns-tab-design-view-styles"><span class="woobewoo-tab-label"><?php echo __('Close Button', WNS_LANG_CODE); ?></span></a>
			<div class="wns-main-nav-children wns-tab-design-view-styles-close-button">

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Override template close button', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::checkbox('settings[view_styles_enable_close_button]', array(
							'checked' => (isset($this->settings['settings']['view_styles_enable_close_button']) ? (int) $this->settings['settings']['view_styles_enable_close_button'] : '')
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option  wnsLoader wnsColorObserver">
					<div class="col-md-6">
						<?php _e('Background color and opacity', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::colorpicker('settings[view_styles_close_button_background]', array(
							'value' => (isset($this->settings['settings']['view_styles_close_button_background']) ? $this->settings['settings']['view_styles_close_button_background'] : 'rgba(255,255,255,1)'),
							'attrs' => 'style="width: 50px"',
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option  wnsLoader wnsColorObserver">
					<div class="col-md-6">
						<?php _e('Background hover color and opacity', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::colorpicker('settings[view_styles_close_button_background_hover]', array(
							'value' => (isset($this->settings['settings']['view_styles_close_button_background_hover']) ? $this->settings['settings']['view_styles_close_button_background_hover'] : 'rgba(255,255,255,1)'),
							'attrs' => 'style="width: 50px"',
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option  wnsLoader wnsColorObserver">
					<div class="col-md-6">
						<?php _e('Borders color and opacity', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::colorpicker('settings[view_styles_close_button_border_color]', array(
							'value' => (isset($this->settings['settings']['view_styles_close_button_border_color']) ? $this->settings['settings']['view_styles_close_button_border_color'] : 'rgba(255,255,255,1)'),
							'attrs' => 'style="width: 50px"',
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option  wnsLoader wnsColorObserver">
					<div class="col-md-6">
						<?php _e('Close button icon color', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::colorpicker('settings[view_styles_close_button_icon_color]', array(
							'value' => (isset($this->settings['settings']['view_styles_close_button_icon_color']) ? $this->settings['settings']['view_styles_close_button_icon_color'] : 'rgba(255,255,255,1)'),
							'attrs' => 'style="width: 50px"',
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Borders width', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::text('settings[view_styles_close_button_border_width]', array(
							'value' => isset($this->settings['settings']['view_styles_close_button_border_width']) ? $this->settings['settings']['view_styles_close_button_border_width'] : '1px',
							'attrs' => 'placeholder="1px" class=""'));
						?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Borders style', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::selectbox('settings[view_styles_close_button_border_style]', array(
							'options' => array(
								'none' => 'none',
								'solid' => 'solid',
								'dotted' => 'dotted',
								'dashed' => 'dashed',
								'double' => 'double',
								'groove' => 'groove',
								'ridge' => 'ridge',
								'inset' => 'inset',
								'outset' => 'outset',
							),
							'value' => (isset($this->settings['settings']['view_styles_close_button_border_style']) ? $this->settings['settings']['view_styles_close_button_border_style'] : 'solid'),
						))?>
					</div>
				</div>

				<div class="row wns-tab-col-row-option">
					<div class="col-md-6">
						<?php _e('Borders radius', WNS_LANG_CODE)?>:
					</div>
					<div class="col-md-6">
						<?php echo htmlWns::text('settings[view_styles_close_button_border_radius]', array(
							'value' => isset($this->settings['settings']['view_styles_close_button_border_radius']) ? $this->settings['settings']['view_styles_close_button_border_radius'] : '4px',
							'attrs' => 'placeholder="4px" class=""'));
						?>
					</div>
				</div>
			</div>
	</div>
	<a href="#" class="wns-link" data-action="stay" data-children="wns-tab-design-custom-css" data-parent="wns-tab-design"><span class="woobewoo-tab-label"><?php echo __('Custom CSS', WNS_LANG_CODE); ?></span></a>
	<div class="wns-main-nav-children wns-tab-design-custom-css">
		<div class="row wns-tab-col-row-option">
			<div class="col-md-12">
				<?php
				echo htmlWns::textarea('settings[custom_css]', array(
					'value' => ( isset($this->settings['settings']['custom_css']) ) ? stripslashes($this->settings['settings']['custom_css']) : '',
					'attrs' => 'id="customCss"',
				))?>
			</div>
		</div>
	</div>
</div>
