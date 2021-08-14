<?php
namespace Home\Controller;

use Think\Controller;
	class UserController extends BaseController {

		public function sign(){
            if(!$_POST['user_name']){
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'not enough parameters: user_name';
                $this->ajaxReturn($ret);
            }
            if(!$_POST['user_id']){
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'not enough parameters: user_id';
                $this->ajaxReturn($ret);
            }
            if(!$_POST['password']){
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'not enough parameters: password';
                $this->ajaxReturn($ret);
            }
            if(!$_POST['password_again']){
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'not enough parameters: password_again';
                $this->ajaxReturn($ret);
            }

            if($_POST['password_again'] != $_POST["password"] ){
                $ret = array();
                $ret['error_code'] = 2;
                $ret['error_msg'] = 'different password';
                $this->ajaxReturn($ret);
            }

            $where = array();
            $where['user_id'] = $_POST['user_id'];

            $User = M('User');
            $user = $User->where($where)->find();
            if($user){
                $ret = array();
                $ret['error_code'] = 3;
                $ret['error_msg'] = 'user already exists!';

                $this->ajaxReturn($ret);
            }
            else{
                $new_user = array();
                $new_user['user_name'] = $_POST['user_name'];
                $new_user['user_id'] = $_POST['user_id'];
                $new_user['password'] = md5($_POST['password']);

                $result = $User->add($new_user);
                if($result){
                    $ret = array();
                    $ret['error_code'] = 0;
                    $ret['error_msg'] = 'add user successfully!';
                    $ret['data'] = $new_user;
                    $this->ajaxReturn($ret);
                }
                else{
                    $ret = array();
                    $ret['error_code'] = 4;
                    $ret['error_msg'] = 'unknown exception!';
                    $this->ajaxReturn($ret);
                }

            }

            dump($_POST);
		}


		public function login(){
            if(!$_POST['user_id']){
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'not enough parameters: user_id';
                $this->ajaxReturn($ret);
            }
            if(!$_POST['password']){
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'not enough parameters: password';
                $this->ajaxReturn($ret);
            }
            $where = array();
            $where['user_id'] = $_POST['user_id'];

            $User = M('User');
            $user = $User->where($where)->find();
            if(!$user){
                $ret = array();
                $ret['error_code'] = 2;
                $ret['error_msg'] = 'no such user!';
                $this->ajaxReturn($ret);
            }
            else if (md5($_POST['password']) != $user['password']) {
                $ret = array();
                $ret['error_code'] = 3;
                $ret['error_msg'] = 'wrong password!';
                $this->ajaxReturn($ret);
            }
            else {
                $ret = array();
                $ret['error_code'] = 0;
                $ret['error_msg'] = 'login success!';
                $ret['data'] = $user;
                $this->ajaxReturn($ret);
            }


        }
}
