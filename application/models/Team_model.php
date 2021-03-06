<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_Model extends MY_Model {
    
    public $_tableName = 'team';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    public function isTitleNotUsed($title,$param){
		if(!$this->getCount(
			array(
				'where' => array('title' => $title , 'd4' => $param)
			))
		){
			return true;
		}else{
			return false;
		}
		
	}
	
	public function userCategoryTeamCount($category,$uid){
		
		//@todo delete 
		
		//return true;
    	$cnt = $this->getCount(array(
    		'where' => array(
				'category_id' => $category,
				'add_uid' => $uid
    		)
    	
    	));
    	
    	
    	if($cnt >= 3){
    		return false;
    	}else{
    		return true;
    	}
    }
}