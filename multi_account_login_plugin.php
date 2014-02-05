<?php
class MultiAccountLoginPlugin extends Plugin {
	public function __construct(){
		$this->loadConfig(dirname(__FILE__) . DS . "config.json");
		
		Language::loadLang("multi_account_login_plugin", null, PLUGINDIR . "multi_account_login" . DS . "language" . DS);
	}
	
	public function getEvents(){
		return array(
			array(
				"event" => "Users.login",
				"callback" => array("this", "account_sessions")
			)
		);
	}
	
	public function getActions(){
		return array(
			/** Generates the link found on the client's home **/
			array(
				'action' => "nav_primary_client",
				'uri' => "plugin/multi_account_login/client_select/",
				'name' => Language::_("MultiAccountLoginPlugin.name", true)
			)
		);
	}
	
	/**
	 * Triggered when a user logs in.
	 *
	 * Gets all of the companies said user is a part of.
	 **/
	public function account_sessions($event){
		$params = $event->getParams();
		
		$user_id = $params['user_id'];
		
		Loader::loadModels($this, array("MultiAccountLogin.AccountSelect"));
		
		if($this->AccountSelect->is_admin($user_id))
			return;
		
		$data = $this->AccountSelect->FetchByUserID($user_id);
		
		$email = $data[0];
		$accounts = $data[1];
		
		Loader::loadComponents($this, array("Session"));
		$this->Session->write("mal_company_data", serialize($accounts));
		$this->Session->write("mal_email", $email);
	}
}
?>
