<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_Service extends Base_Service {

	private $_teamModel;
	private $_teamMemberModel;
	private $_sportsCategoryModel;
	private $_districtStatModel;


	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Team_Model');
		self::$CI->load->model('Team_Member_Model');
		
		self::$CI->load->model('Sports_Category_Model');
		self::$CI->load->model('District_Stat_Model');
		
		
		$this->_teamModel = self::$CI->Team_Model;
		$this->_teamMemberModel = self::$CI->Team_Member_Model;
		$this->_sportsCategoryModel = self::$CI->Sports_Category_Model;
		$this->_districtStatModel = self::$CI->District_Stat_Model;
	}
	
	
	/**
	 * 更新球队信息
	 */
	public function updateTeamInfo($data,$id){
		return $this->_teamModel->update($data,array('id' => $id));
	}
	
	public function getAllPagerTeam($search = array(),$page = 1, $pageSize = 10)
	{
		$search['pager'] = array(
			'current_page' => $page,
			'page_size' => $pageSize
		);
		
		$data = $this->_teamModel->getList($search);
		return $data;
	}
	
	
	
	
	/**
	 * 获得活动分类
	 */
	public function getSportsCategory($condition = array())
	{
		$condition['where']['status'] = 0;
		
		return $this->toEasyUseArray($this->_sportsCategoryModel->getList($condition));
	}
	
	/**
	 * 根据条件获得队伍
	 */
	public function getTeamListByCondition($condition){
		return $this->toEasyUseArray($this->_teamModel->getList($condition));
	}
	
	/**
	 * 
	 */
	public function getTeamInfo($teamid ,$withMemeber = true){
		
		$team['basic'] = $this->_teamModel->getById(array(
			'where' => array('id' => $teamid)
		));
		
		//$team['members'] = array();
		
		if($withMemeber){
			$team['members'] = $this->_teamMemberModel->getList(array(
				'where' => array('team_id' => $teamid)
			));
		}
		
		return $team;
	}
	
	
	/**
	 * 生成邀请链接
	 * 加密参数 队伍和用户信息到里面
	 */
	public function generateInviteUrl($teamInfo,$userInfo){
		//teamid,uid,有效期
		// 24小时内有效
		$expire = time() + 3600 * 24;
		
		//teamid + uid + timestamp
		$text = "{$teamInfo['id']}\t{$userInfo['uid']}\t{$expire}";
		$encrypted_string = self::$CI->encrypt->encode($text, config_item('encryption_key'));
		
		return site_url('team/invite/?param='.urlencode($encrypted_string));
	}
	
	/**
	 * 用户加入队伍
	 */
	public function joinTeam($teamid,$userinfo){
		
		$message = array(
			'join_in' => false,
			'new_member' => false
		);
		
		
		$notJoined = $this->_teamMemberModel->getCount(array(
			'where' => array(
				'team_id' => $teamid,
				'uid' => $userinfo['uid']
			)
		));
		
		
		if($notJoined == 0){
			$message['new_member'] = true;
			
			$id = $this->_teamMemberModel->_add(array(
				'uid' => $userinfo['uid'],
				'nickname' => $userinfo['nickname'],
				'username' => $userinfo['username'],
				'avatar_middle' => $userinfo['avatar_middle'],
				'team_id' => $teamid
			));
			
			if($id > 0){
				$this->_teamModel->increseOrDecrease(array(
					array('key' => 'current_num','value' => 'current_num+1')
				),array('id' => $teamid));
			}
		}
		
		$message['join_in'] = true;
		return $message;
		
		
	}
	
	/**
	 * 增加队伍验证规则
	 *  
	 * @param array  $userInfo  登陆用户信息
	 * 
	 */
	public function teamAddRules($userInfo = array()){
		self::$form_validation->reset_validation();
		
		$rule = array();
		
		$rule['category_id'] = array(
			'field' => 'category_id',
			'label' => '队伍类型',
			'rules' => array(
				'required',
				'is_natural_no_zero',
				array(
					'category_callable',
					array(
						$this->_sportsCategoryModel,'avaiableCategory'
					)
				)
			),
			'errors' => array(
				'category_callable' => '%s无效'
			)
		);
		
		
		if($userInfo){
			$rule['category_id']['rules'][] = array(
					'user_categroy_callbale['.$userInfo['uid'].']',
					array(
						$this->_teamModel,'userCategoryTeamCount'
					)
				);
				
			$rule['category_id']['errors']['user_categroy_callbale'] = '对不起,同一个类型的球队最多创建三个';
		}
		
		self::$form_validation->set_rules($rule['category_id']['field'],$rule['category_id']['label'],$rule['category_id']['rules'],$rule['category_id']['errors']);


		//队伍名称允许相同,因现实情况下确实有可能相同
		//用户如果设置 d4 级的话， 则校验名称重复，如果d4 级没有设置，则不校验
		
		if($userInfo['d4'] > 0){
			//获得地区名称
			$districtName = self::$districtModel->getById(array(
				'select' => 'name',
				'where' => array('id' => $userInfo['d4'])
			));
			
			self::$form_validation->set_rules('title','球队名称', array(
					'required',
					'max_length[4]',
					array(
						'title_callable['.$userInfo['d4'].']',
						array(
							$this->_teamModel,'isTitleNotUsed'
						)
					)
				),
				array(
					'title_callable' => '%s '.self::$CI->input->post('title').'在'.$districtName['name'].'已经存在'
				)
			);
		}else{
			self::$form_validation->set_rules('title','球队名称', 'required|max_length[4]');
		}
		
		self::$form_validation->set_rules('leader','队长设置','required|in_list[1,2]');
		self::$form_validation->set_rules('joined_type','入队设置','required|in_list[1]');
	}
	
	
	
	/**
	 * 添加队伍
	 */
	public function addTeam($param,$creatorInfo){
		
		// 启动事务
		$this->_teamModel->transStart();
		
		/*
		$cateName = $this->_sportsCategoryModel->getById(array(
			'select' => 'name',
			'where' => array('id' => $param['category_id'])
		));
		$param['category_name'] = $cateName['name'];
		*/
		
		$param['owner_uid'] = $creatorInfo['uid'];
		$param['d1'] = $creatorInfo['d1'];
		$param['d2'] = $creatorInfo['d2'];
		$param['d3'] = $creatorInfo['d3'];
		$param['d4'] = $creatorInfo['d4'];
		
		$dIDs = array($param['d1'],$param['d2'],$param['d3'],$param['d4']);
		$dNameList = self::$districtModel->getList(array(
			'select' => 'id,name',
			'where_in' => array(
				array('key' => 'id', 'value' => $dIDs)
			)
		));
		
		$dName = array();
		foreach($dNameList as $dn){
			$dName[$dn['id']] = $dn['name'];
		}
		
		$param['dname1'] = $dName[$param['d1']];
		$param['dname2'] = $dName[$param['d2']];
		$param['dname3'] = $dName[$param['d3']];
		$param['dname4'] = empty($dName[$param['d4']]) ? '' : $dName[$param['d4']];
		
		
		$teamid = $this->_teamModel->_add($param);
		
		if ($this->_teamModel->transStatus() === FALSE){
			$this->_teamModel->transRollback();
			return false;
		}
		
		$memberid = $this->_teamMemberModel->_add(array(
			'uid' => $creatorInfo['uid'],
			'nickname' => $creatorInfo['nickname'],
			'username' => $creatorInfo['username'],
			'avatar_middle' => $creatorInfo['avatar_middle'],
			'team_id' => $teamid,
			'rolename' => $param['leader_uid'] == 0 ? '队员' : '队长'
		));
		
		
		if ($this->_teamModel->transStatus() === FALSE){
			$this->_teamModel->transRollback();
			return false;
		}
		
		
		$stat_id = md5($param['category_id'].$creatorInfo['d1'].$creatorInfo['d2'].$creatorInfo['d3'].$creatorInfo['d4']);
		$cnt = $this->_districtStatModel->getCount(array(
			'where' => array(
				'id' => $stat_id
			)
		));
		
		if($cnt > 0){
			//update
			$this->_districtStatModel->increseOrDecrease(array(
				array('key' => 'teams' ,'value' => 'teams + 1')
			),array('id' => $stat_id));
		}else{
			//add
			
			/*
			if($param['d4'] > 0){
				$district = self::$districtModel->getFirstByKey($param['d4']);
			}else{
				$district = self::$districtModel->getFirstByKey($param['d3']);
			}
			*/
			
			$this->_districtStatModel->_add(array(
				'id' => $stat_id,
				'category_id' => $param['category_id'],
				'd1' => $creatorInfo['d1'],
				'd2' => $creatorInfo['d2'],
				'd3' => $creatorInfo['d3'],
				'd4' => $creatorInfo['d4'],
				//'dname' => $district['name'] == '' ? '' : $district['name'],
				'teams' => 1
			));
		}
		
		if ($this->_teamModel->transStatus() === FALSE){
			$this->_teamModel->transRollback();
			
			return false;
		}
		
		$this->_teamModel->transCommit();
		$this->_teamModel->transOff();
		
		return $teamid;
	}
	
	
	public function isTeamManager($team,$userInfo){
		
		$canManager = false;
		
		$teamOwnerUid = $team['basic']['leader_uid'];
		if(!$teamOwnerUid){
			$teamOwnerUid = $team['basic']['owner_uid'];
		}
		
		if($userInfo['uid'] == $teamOwnerUid){
			$canManager = true;
		}
		
		return $canManager;
	}
	
	
	/**
	 * 更新队伍 和队员信息
	 */
    public function manageTeam($team,$members){
    	
    	$this->_sportsCategoryModel->transStart();
    	
    	$teamid = $team['id'];
    	
    	//unset($team['id']);
    	$this->_teamModel->update($team,array('id' => $team['id']));
    	
    	if ($this->_sportsCategoryModel->transStatus() === FALSE){
			$this->_sportsCategoryModel->transRollback();
			return false;
		}
    	
    	$this->_teamMemberModel->batchUpdate($members);
    	
    	if ($this->_sportsCategoryModel->transStatus() === FALSE){
			$this->_sportsCategoryModel->transRollback();
			return false;
		}
    	
    	$this->_sportsCategoryModel->transCommit();
		$this->_sportsCategoryModel->transOff();
    	
    	return true;
    	
    }
    
}