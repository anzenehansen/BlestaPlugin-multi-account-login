<?php
class ClientSelect extends MultiAccountLoginController {
    public function preAction(){
        parent::preAction();
	
	Loader::loadComponents($this, array("Session", "Record"));
	
        // Restore structure view location of the client portal
        $this->structure->setDefaultView(APPDIR);
        $this->structure->setView(null, $this->orig_structure_view);
	
	Language::loadLang("client_select", null, PLUGINDIR . "multi_account_login" . DS . "language" . DS);
    }

    public function index(){
	if(!empty($this->post)){
	    $ids = $this->post['user'];
	    $parts = explode(",", $ids);
	    
	    $uid = $parts[0];
	    $cid = $parts[1];
	    
	    $this->Session->write("blesta_id", $uid);
	    $this->Session->write("blesta_client_id", $cid);
	    $this->redirect($this->base_uri);
	} else{
	    $data = unserialize($this->Session->read("mal_company_data"));
	}
	
	$this->set("data", $data);
    }
}
