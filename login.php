<?php
	require('./include/init.php');
	if(isset($_POST['username']) && isset($_POST['passwd'])){
		$model=new UserModel();
		$row=$model->checkuser($_POST['username'],$_POST['passwd']);
		if($row!==false){
			session_start();

			//登录后,重置session的内容，并将用户信息放入session
			$_SESSION=$row;
			
			if(isset($_POST['remember']) && $_POST['remember']==1){
				setcookie('username',$row['username'],time()+3600*24);
			}else{
				setcookie('username','',time());
			}
			$msg='登录成功';
		}else{
			$msg='登录失败';	
		}
		include(ROOT.'view/front/msg.html');
	}else{
		include(ROOT.'view/front/denglu.html');
	}
 ?>