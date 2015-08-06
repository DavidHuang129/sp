<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment_Service extends Base_Service {

	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Attachment_Model');
		
	}
	
	public function setExtraInfo($fileInfo, $config){
		
		$fileInfo['file_url'] = $config['file_path'].$fileInfo['file_name'];
		$fileInfo['ip'] = $this->CI->input->ip_address();
		
		return $fileInfo;
	}
	
	public function getImageConfig(){
		
		$config['file_path'] = 'static/attach/'.date("Y/m/d/");
        $config['upload_path'] = ROOTPATH . '/'.$config['file_path'];
        
        make_dir($config['upload_path']);
        
		$config['allowed_types'] = 'jpg';
		$config['file_ext_tolower'] = true;
		$config['encrypt_name'] = true;
		
		return $config;
	}
	
	/**
	 * 添加附件信息
	 */
	public function addImageAttachment($filename, $moreConfig = array()){
		
		//处理照片
		$config = $this->getImageConfig();
		
		if(!empty($moreConfig)){
			$config = array_merge($config,$moreConfig);
		}
		
		//print_r($config);
		
		$this->CI->load->library('upload', $config);
		if($this->CI->upload->do_upload($filename)){
			$fileData = $this->CI->upload->data();
			
			//print_r($fileData);
			
			$fileData = $this->setExtraInfo($fileData,$config);
			
			$file_id = $this->CI->Attachment_Model->_add($fileData);
			$fileData['id'] = $file_id;
			
			return $fileData;
		}else{
			
			//echo $this->CI->upload->display_errors();
			return false;
		}
		
	}
	
	/**
	 *  显示错误信息
	 */
	public function getErrorMsg($open = '<div class="form_error">',$close = '</div>'){
		return $this->CI->upload->display_errors($open,$close);
	}
	
}