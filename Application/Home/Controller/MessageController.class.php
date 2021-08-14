<?php
namespace Home\Controller;

use http\Message;
use Think\Controller;
class MessageController extends BaseController {

        public function post_artical(){
            if(!$_POST['user_id']){
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'not enough parameters: user_id';
                $this->ajaxReturn($ret);
            }
            if(!$_POST['content']){
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'not enough parameters: content';
                $this->ajaxReturn($ret);
            }


            $Message = M('Message');
            $User = M('User');

            $where = array();
            $where['user_id'] = $_POST['user_id'];

            $user = $User->where($where)->find();

            $data = array();
            $data['user_id'] = $_POST['user_id'];
            $data['user_name'] = $user['user_name'];
            $data['content'] = $_POST['content'];
            $data['send_timestamp'] = time();
            $data['likes'] = 0;

            $result = $Message->add($data);
            if($result)
            {
                $ret = array();
                $ret['error_code'] = 0;
                $ret['error_msg'] = 'post artical successfully!';
                $ret['dats'] = $data;
                $this->ajaxReturn($ret);
            }
            else{
                $ret = array();
                $ret['error_code'] = 2;
                $ret['error_msg'] = 'unknown exception!';
                $this->ajaxReturn($ret);
            }

        }

        public function  get_all_artical(){
            $Message  = M('Message');

            $all_message = $Message->order('id desc')->select();

            foreach ($all_message as $key => $message){
                $all_message[$key]['send_timestamp'] = date('Y-m-d H:i:s', $message['send_timestamp']);
            }

            $ret = array();
            $ret['error_msg'] = 'get all message successfully!';
            $ret['error_code'] = 0;
            $ret['data'] = $all_message;

            $this->ajaxReturn($ret);
//            dump($all_message);
        }

        public function  like(){
            if(!$_POST['user_id']){
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'not enough parameters: user_id';
                $this->ajaxReturn($ret);
            }
            if(!$_POST['artical_id']) {
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'not enough parameters: artical_id';
                $this->ajaxReturn($ret);
            }

            //check if user exists
            $User = M('User');
            $user = array();
            $user['user_id'] = $_POST['user_id'];
            $if_user_exists = $User->where($user)->find();
            if(!$if_user_exists){
                $ret = array();
                $ret['error_code'] = 1;
                $ret['error_msg'] = 'no such user!';
                $this->ajaxReturn($ret);
            }

            //check if msg exists
            $Message  = M('Message');
            $where = array();
            $where['id'] = $_POST['artical_id'];

            $message = $Message->where($where)->find();
            if($message){
                $data = array();
                $data['likes'] = $message['likes']+1;

                $result = $Message->where($where)->save($data);
                if($result) {
                    $ret = array();
                    $ret['error_code'] = 0;
                    $ret['error_msg'] = 'like successfully';
                    $this->ajaxReturn($ret);
                }
                else{
                    $ret = array();
                    $ret['error_code'] = 3;
                    $ret['error_msg'] = 'unknown exception!';
                    $this->ajaxReturn($ret);
                }
            }
            else{
                $ret = array();
                $ret['error_code'] = 2;
                $ret['error_msg'] = 'no such artical!';
                $this->ajaxReturn($ret);
            }

        }
}

