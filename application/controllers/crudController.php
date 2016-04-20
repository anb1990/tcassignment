<?php
class crudController extends Controller {    

    protected function init(){    
        $this->db = new DBAdapter($this->cfg['db']['hostname'], $this->cfg['db']['username'], 
        $this->cfg['db']['password'], $this->cfg['db']['database']);
    }
	
	public function index(){
		$data = $this->_model->read();
			$this->view->set('items',$data);
		return $this->view();
		
	}
	
	
}
