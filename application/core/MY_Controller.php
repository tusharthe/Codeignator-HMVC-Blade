<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."third_party/BladeOne/BladeOne.php";
Use eftec\bladeone;

class MY_Controller extends MX_Controller {
	

	protected $views;
	protected $cache;
	protected $blade;
	protected $_module_location_dir;
	protected $_module_location_url;	
	protected $_module;

	function __construct()
		{
			parent::__construct();	
			if($this->config->item('Blade_enable'))	
			{
				try {
					$this->get_module_dir();
					$this->blade = new bladeone\BladeOne($this->views,$this->cache);
					$this->blade->setBaseUrl(base_url()); 				
				} catch (Exception $ex) {		
					echo "Your details are wrong. <br>";			
					 // $ex->getMessage() has a detailed message
				}
			}	
			
		}


	protected function get_module_dir()
	{
		if($this->config->item('HMVC_enable')){

			$this->_module = CI::$APP->router->fetch_module();

			is_array($this->_module_location_dir = $this->config->item('modules_locations')) 
			OR 
			$this->_module_location_dir = array(	APPPATH.'modules/' => '../modules/', );

			$this->_module_location_url = reset($this->_module_location_dir);
			$this->_module_location_url = str_replace('/',"",$this->_module_location_url);
			$this->_module_location_url = str_replace('.',"",$this->_module_location_url);
			$this->_module_location_url = $this->_module_location_url.'/'.$this->_module.'/';


			$this->_module_location_dir = realpath(key($this->_module_location_dir)) . "\\";


					
			$this->views = $this->_module_location_dir. $this->_module . '\views';
			$this->cache = $this->_module_location_dir. $this->_module . '\cache';


		}else{
			$this->views = APPPATH . 'views';
			$this->cache = APPPATH . 'cache';
		}
	}

	
		
	function view($view_name,$array = [])
	{
		if($this->config->item('Blade_enable'))	
		{	
			$array['load'] = $this->load;
			$array['blade'] = $this->blade;
			$array['_module_location_dir'] = $this->_module_location_dir;
			$array['_module_location_url'] = $this->_module_location_url;			
			$array['_module'] = $this->_module;
			echo $this->blade->run($view_name,$array);		
		}
		else
		{
			 $this->load->view($view_name,$array);		
		}
	}
}
