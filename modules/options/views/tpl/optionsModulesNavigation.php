<nav class="woobewoo-navigation woobewoo-sticky <?php dispatcherWns::doAction('adminMainNavClassAdd')?>">
	<ul>
		<?php foreach($this->tabs as $tabKey => $tab) { ?>
			<?php if(isset($tab['hidden']) && $tab['hidden']) continue;?>
			<li class="woobewoo-tab-<?php echo $tabKey;?> <?php echo (($this->activeTab == $tabKey || in_array($tabKey, $this->activeParentTabs)) ? 'active' : '')?>">
				<a class="wns-main-nav-a" href="<?php echo $tab['url']?>" title="<?php echo $tab['label']?>">
					<span class="woobewoo-tab-label"><?php echo $tab['label']?></span>
				</a>
			</li>
		<?php }?>
	</ul>
</nav>
