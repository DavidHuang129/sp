<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function index()
	{	
		/*
		$this->email->from('tdkc_of_cixi@163.com', '运动之家');
		$this->email->to('104071152@qq.com');
		//$this->email->cc('another@another-example.com');
		//$this->email->bcc('them@their-example.com');
		
		$this->email->subject('【运动之家 邮件激活】');
		$this->email->message('尊敬的'.$this->input->post('nickname').'用户,欢迎你加入运动之家， 点击以下链接进行邮件激活,链接2小时内有效');
		if($this->email->send()){
			$this->assign('tip_email',true);
		}
		*/
	}
	
	private function _rememberLoginName($loginName, $liveSeconds = 2592000){
		$this->input->set_cookie('loginname',$loginName, $liveSeconds);
		
	}
	
	/**
	 * 前台 登陆
	 */
	public function login()
	{
		
		if($this->isLogin()){
			redirect('my');
		}
		
		if($this->isPostRequest()){
			
			$this->assign('returnUrl',$this->input->post('returnUrl'));
			$this->form_validation->reset_validation();
			
			$this->form_validation->set_rules('loginname','用户登陆手机', 'required|valid_mobile');
			
			$this->form_validation->set_rules('password','密码','required|alpha_numeric');
			$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
			
			//$this->form_validation->set_rules('returnUrl','返回地址','valid_url');
			
			if($this->form_validation->run() !== FALSE){

				$this->load->library('Member_service');
				
				$result = $this->member_service->do_login(array(
					'mobile' => $this->input->post('loginname'),
					'password' => $this->input->post('password')
				));
				
				
				if($result['code'] == 'success'){
					$this->session->set_userdata(array(
						'profile' => $result['data'],
						'lastvisit' => $this->_reqtime
					));
					
					$this->member_service->updateUserInfo(
						array(
							'sid' => $this->session->session_id,
							'last_login' => $this->_reqtime,
							'last_loginip' => $this->input->ip_address()
						),
						$result['data']['basic']['uid']);
						
						
					$this->_rememberLoginName($this->input->post('loginname'));
					
					$url = $this->input->post('returnUrl');
					if(!empty($url) && isLocalUrl($url)){
						redirect($url);
					}else{
						redirect('my/index');
					}
					
				}else{
					$this->assign('feedback',$result['message']);
				}
			}
		}else{
			//记住用户点击时  因为需要登录的返回链接
			$teamJoinParam = $this->input->get('teamjoin');
			//$string = $this->encrypt->decode($teamJoinParam,$this->config->item('encryption_key'));
			if($teamJoinParam){
				$this->assign('returnUrl',site_url('team/invite/?param='.$teamJoinParam));
			}else{
				$this->assign('returnUrl', $this->input->get('returnUrl'));
			}
			
			$this->assign('loginname',get_cookie('loginname'));
			
			
		}
		
		$this->seoTitle('登陆');
		$this->display();
	}

	/**
	 * 管理后台登陆
	 */
	public function admin_login(){
		
		
		$adminData = $this->session->get_userdata($this->_adminProfileKey);
		$this->assign($this->_adminProfileKey,$adminData);
		
		
		if($this->isPostRequest()){
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('email','登陆邮箱', 'required|valid_email');
			$this->form_validation->set_rules('password','密码','required|alpha_numeric');
			$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
			
			
			for($i = 0; $i < 1; $i++){
				
				if($this->form_validation->run() == FALSE){
					break;
				}
				
				$this->load->library('Admin_service');
				$result = $this->admin_service->do_adminlogin($this->input->post('email'),$this->input->post('password'));
				
				if($result['message'] != '成功'){
					$this->assign('feedback','<div class="form_error">' .$result['message'].'</div>');
					break;
				}
				
				$this->session->set_userdata(array(
					$this->_adminProfileKey => $result['data'],
					$this->_adminLastVisitKey => $this->_reqtime
				));
				
				$this->admin_service->updateUserInfo(
					array(
						'sid' => $this->session->session_id,
						'last_login' => $this->_reqtime,
						'last_loginip' => $this->input->ip_address()
					),
					$result['data']['basic']['uid']);
				
				js_redirect(admin_site_url('index'),'top');
				
			}
		}
		
		$this->seoTitle('后台登陆');
		$this->display();
	}
	
	
	
	
	/**
	 * 用户注册
	 * 
	 */
	public function register()
	{
		
		$registerOk = false;
		$feedback = '';
		
		if($this->isPostRequest()){
			$inviter = $this->input->post('inviter');
			$inviteFrom = $this->input->post('inviteFrom');
			
			$this->assign('inviter', $inviter);
			$this->assign('inviteFrom',$inviteFrom);
			$this->assign('returnUrl',$this->input->post('returnUrl'));
			
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('mobile','手机号',array(
						'required',
						'valid_mobile',
						array(
							'loginname_callable[mobile]',
							array(
								$this->Member_Model,'isUnqiueByKey'
							)
						)
					),
					array(
						'loginname_callable' => '%s已经被注册'
					)
				);
				
			$this->load->library('Verify_service');
			$this->form_validation->set_rules('auth_code','验证码', array(
						'required',
						array(
							'authcode_callable['.$this->input->post('mobile').']',
							array(
								$this->verify_service,'validateAuthCode'
							)
						)
					),
					array(
						'authcode_callable' => '验证码不正确'
					)
				);
			
			/*
			$this->form_validation->set_rules('nickname','昵称', array(
						'required',
						array(
							'nickname_callable[nickname]',
							array(
								$this->Member_Model,'isUnqiueByKey'
							)
						)
					),
					array(
						'nickname_callable' => '%s已经被占用'
					)
				);
			*/
			
			$this->form_validation->set_rules('qq','用户QQ号码', 'required|numeric|min_length[4]|max_length[15]');
			$this->form_validation->set_rules('psw','密码','required|alpha_dash|min_length[6]|max_length[12]');
			$this->form_validation->set_rules('psw_confirm','密码确认','required|matches[psw]');
			//$this->form_validation->set_rules('agreee_licence','同意注册条款','required');
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					break;
				}
				
				$this->load->library(array('Member_service','Register_service'));
				$todayRegistered = $this->register_service->getIpLimit($this->input->ip_address());
				
				if($todayRegistered > 5){
					$feedback = getWarningTip('很抱歉，您今日注册数量已经用完');
					break;
				}
				
				$addParam = array(
					'sid' => $this->session->session_id,
					'mobile' => $this->input->post('mobile'),
					'nickname' => $this->input->post('mobile'),
					'qq' => $this->input->post('qq'),
					'password' => $this->input->post('psw'),
					'status' => -2,
					'inviter' => empty($inviter) == true ? 0 : intval($inviter)
				);
				
				$result = $this->register_service->createMember($addParam);
				
				if($result['code'] != 'success'){
					$feedback = getErrorTip($result['message']);
					break;
				}
				
				$userInfo = $this->member_service->getUserInfoByMobile($this->input->post('mobile'));
				$this->_rememberLoginName($this->input->post('mobile'));
				
				$this->session->set_userdata(array(
					'profile' => array('basic' => $userInfo)
				));
				
				$registerOk = true;
			}
			
		}else{
			/* 邀请人 */
			$this->assign('inviter', $this->input->get('inviter'));
		}
		
		$this->assign('feedback',$feedback);
		
		if($registerOk){
			redirect('my');
		}else{
			$this->seoTitle('用户注册');
			$this->display();
		}
	}
	
	/**
	 * 登出
	 */
	public function logout(){
		
		if($this->isLogin()){
			$this->session->unset_userdata('profile');
		}
		
		redirect('member/login');
	}
	
	
}
