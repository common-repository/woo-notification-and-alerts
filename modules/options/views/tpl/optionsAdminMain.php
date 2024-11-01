<style type="text/css">
	.wnsAdminMainLeftSide {
		width: 56%;
		float: left;
	}
	.wnsAdminMainRightSide {
		width: <?php echo (empty($this->optsDisplayOnMainPage) ? 100 : 40)?>%;
		float: left;
		text-align: center;
	}
	#wnsMainOccupancy {
		box-shadow: none !important;
	}
</style>
<section>
	<div class="supsystic-item supsystic-panel">
		<div id="containerWrapper">
			<?php _e('Main page Go here!!!!', WNS_LANG_CODE)?>
		</div>
		<div style="clear: both;"></div>
	</div>
</section>