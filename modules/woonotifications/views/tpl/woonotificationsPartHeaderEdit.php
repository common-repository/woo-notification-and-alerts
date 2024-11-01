
				<div class="col-md-8">
					<div class="col-md-6 wnsNamePadding">
						<span id="wnsNotificationTitleShell" title="<?php echo esc_html(__('Click to edit', WNS_LANG_CODE))?>">
							<i class="fa fa-fw fa-pencil"></i>
							<?php $title = isset($this->notificationSettings['title']) ? $this->notificationSettings['title'] : 'empty';?>
							<span id="wnsNotificationTitleLabel"><?php echo $title; ?></span>
							<?php echo htmlWns::text('title', array(
								'value' => $title,
								'attrs' => 'style="display:none;" id="wnsNotificationTitleTxt"',
								'required' => true,
							)); ?>
						</span>
					</div>
					<div class="col-md-2 wnsShortcodeAdm">
						<select name="shortcode_example" id="wnsCopyTextCodeExamples">
							<option value="shortcode"><?php echo __('Shortcode', WNS_LANG_CODE); ?></option>
							<option value="phpcode"><?php echo __('PHP code', WNS_LANG_CODE); ?></option>
						</select>
					</div>
					<?php $id = isset($this->notificationSettings['id']) ? $this->notificationSettings['id'] : ''; ?>
					<?php if($id) {?>
					<div class="col-md-4 wnsCopyTextCodeShowBlock wnsShortcode shortcode" style="">
						<?php
							echo htmlWns::text('', array(
									'value' => "[".WNS_SHORTCODE." id=$id]",
									'attrs' => 'readonly style="width: 100%" onclick="this.setSelectionRange(0, this.value.length);" class=""',
									'required' => true,
								));
						?>
					</div>
					<div class="col-md-4 wnsCopyTextCodeShowBlock wnsShortcode phpcode" style="display: none;">
						<?php
							echo htmlWns::text('', array(
								'value' => "<?php echo do_shortcode('[".WNS_SHORTCODE." id=$id]') ?>",
								'attrs' => 'readonly style="width: 100%" onclick="this.setSelectionRange(0, this.value.length);" class=""',
								'required' => true,
							));
						?>
					</div>
					<?php } ?>
					<div class="clear"></div>
				</div>
				<div class="col-md-4">
					<div class="wnsMainBtnsShell">
						<ul class="wnsSub control-buttons">
							<li>
								<button id="buttonSave">
									<span><i class="fa fa-spinner" aria-hidden="true"></i> <?php echo __('Save', WNS_LANG_CODE); ?></span>
								</button>
							</li>
							<li>
								<button id="buttonClone" data-filter-id="<?php echo $id;?>" class="wnsDuplicateFilter">
									<span><i class="fa fa-spinner" aria-hidden="true"></i> <?php echo __('Clone', WNS_LANG_CODE); ?></span>
								</button>
							</li>
							<li>
								<button id="buttonDelete">
									<span><i class="fa fa-spinner" aria-hidden="true"></i> <?php echo __('Delete', WNS_LANG_CODE); ?></span>
								</button>
							</li>
						</ul>
					</div>
				</div>
