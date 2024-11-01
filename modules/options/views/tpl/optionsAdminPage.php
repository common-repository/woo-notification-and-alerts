<div class="container-fluid woobewoo-wrapper">
		<header class="row woobewoo-header">
			<?php echo $this->header ?>
		</header>
		<div class="row woobewoo-content-wrapper">
			<?php echo $this->content ?>
		</div>
</div>

<!--Option available in PRO version Wnd-->
<div id="wnsOpt" style="display: none;" title="qwe">

</div>

<div id="wnsAddDialog" style="display: none;" title="<?php _e('Enter notification name', WNS_LANG_CODE)?>">
	<div style="">
		<form id="tableForm">
			<input id="addDialog_title" class="supsystic-text" type="text" style="width:100%;"/>
			<input type="hidden" id="addDialog_duplicateid" class="supsystic-text" style="width:100%;"/>
		</form>
		<div id="formError" style="color: red; display: none; float: left;">
			<p></p>
		</div>
		<!-- /#formError -->
	</div>
</div>
<!-- /#addDialog -->
