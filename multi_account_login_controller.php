<?php
class MultiAccountLoginController extends AppController {
	public function preAction(){
		parent::preAction();

		$this->view->view = "default";
		$this->orig_structure_view = $this->structure->view;
		$this->structure->view = "default";
	}
}
?>
