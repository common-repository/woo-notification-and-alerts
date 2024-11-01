<?php
class pagesViewWns extends viewWns {
    public function displayDeactivatePage() {
        $this->assign('GET', reqWns::get('get'));
        $this->assign('POST', reqWns::get('post'));
        $this->assign('REQUEST_METHOD', strtoupper(reqWns::getVar('REQUEST_METHOD', 'server')));
        $this->assign('REQUEST_URI', basename(reqWns::getVar('REQUEST_URI', 'server')));
        parent::display('deactivatePage');
    }
}

