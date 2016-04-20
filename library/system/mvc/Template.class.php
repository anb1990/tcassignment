<?php
class Template {                      
	protected $_variables = array(),
	          $_controller,
	          $_action,
              $_bodyContent;
              
    public    $viewPath, 
              $section = array(),
              $layout;
    
	public function __construct($controller, $action) {
		$this->_controller = $controller;
		$this->_action = $action;
        global $cfg;
        $this->set('cfg',$cfg);
	}

	/** 
	 * Set Variables 
	 */
	public function set($name, $value) {
		$this->_variables[$name] = $value;
	}
    
    /**
    * set action
    */
    public function setAction($action){
        $this->_action = $action;
    }
    
    /**
    * RenderBody
    */
    public function renderBody(){
    	// if we have content, then deliver it
        if(!empty($this->_bodyContent)){
            echo $this->_bodyContent;
        }
    }
    
   

	/** 
	* Display Template 
	*/
    public function render() {		
        extract($this->_variables);
        $path = MyHelpers::UrlContent('~/views/');
        ob_start();
        // render page content
        if(empty($this->viewPath)){ 
            include ($path . $this->_controller . '/' . $this->_action . '.php');
        }else{
            include ($this->viewPath);
        }
        // get the body contents
        $this->_bodyContent = ob_get_contents();
        ob_end_clean();
        
            echo $this->_bodyContent;
        ob_end_flush();
    }
    
    /**
    * return the renderred html string
    */
    public function __toString(){
        $this->render();
        return '';
    }
}   