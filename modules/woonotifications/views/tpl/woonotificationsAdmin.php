<section>
    <div class="supsystic-item supsystic-panel">
        <div id="containerWrapper">
            <ul id="wnsTableTblNavBtnsShell" class="supsystic-bar-controls">
                <li title="<?php _e('Delete selected', WNS_LANG_CODE)?>">
                    <button class="button" id="wnsTableRemoveGroupBtn" disabled data-toolbar-button>
                        <i class="fa fa-fw fa-trash-o"></i>
						<?php _e('Delete selected', WNS_LANG_CODE)?>
                    </button>
                </li>
                <li title="<?php _e('Search', WNS_LANG_CODE)?>">
                    <input id="wnsTableTblSearchTxt" type="text" name="tbl_search" placeholder="<?php _e('Search', WNS_LANG_CODE)?>">
                </li>
            </ul>
            <div id="wnsTableTblNavShell" class="supsystic-tbl-pagination-shell"></div>
            <div style="clear: both;"></div>
            <hr />
            <table id="wnsTableTbl"></table>
            <div id="wnsTableTblNav"></div>
            <div id="wnsTableTblEmptyMsg" style="display: none;">
                <h3><?php printf(__('You have no Notifications for now. <u><a href="%s" style="font-style: italic;">Create</a></u> your Notification!', WNS_LANG_CODE), $this->addNewLink)?></h3>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div id="prewiew" style="margin-top: 30px"></div>
    </div>
</section>
