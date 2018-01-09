<?php

class Controller_profile extends Controller {

	public function index() {
		$user_model = Controller::loadModel("user");
		$userInfo = $user_model->getUserInfo($_SESSION['id']);

        if(isset($_SESSION['itogInsert'])){
            $dataCSV['itogInsert'] = $_SESSION['itogInsert'];
        }else{
            $dataCSV['itogInsert'] = 'ss';
        }
        if(isset($_SESSION['itogNoValid'])){
            $dataCSV['itogNoValid'] = $_SESSION['itogNoValid'];
        }else{
            $dataCSV['itogNoValid'] = 'ss';
        }

		if (isset($_REQUEST['login'])) {
			$this->validate();

			$userInfo = array(
				'login' => $_REQUEST['login'],
				'first_name' => $_REQUEST['first_name'],
				'last_name' => $_REQUEST['last_name'],
				'email' => $_REQUEST['email']);

			if (strlen($_REQUEST['pwd1']) > 0)
				$userInfo['password'] = md5($_REQUEST['pwd1']);

			if (empty($this->error)) {
				if ($user_model->editUser($_SESSION['id'], $userInfo)) {
					
				} else {
					$this->error[] = $user_model->getLastError();
				}
			}
		}

		$header_view = new View("header");
		$profile_view = new View("profile");
		$footer_view = new View("footer");
        $csv_upload_view = new View("upload");

		$header['title'] = "Profile info";
		$data['action'] = "/profile";
		$data['error'] = $this->error;
		$data['login'] = $userInfo['login'];
		$data['first_name'] = $userInfo['first_name'];
		$data['last_name'] = $userInfo['last_name'];
		$data['email'] = $userInfo['email'];
		$data['submit_button'] = 'Change';
		$data['admin'] = ($_SESSION['role'] == 1) ? true : false;

        $dataCSV['action'] = "/profile/upload";
        $dataCSV['error'] = $this->error;
        $dataCSV['login'] = $userInfo['login'];
        $dataCSV['first_name'] = $userInfo['first_name'];
        $dataCSV['last_name'] = $userInfo['last_name'];
        $dataCSV['email'] = $userInfo['email'];

		$header_view->setData($header);
		$profile_view->setData($data);
        $csv_upload_view->setData($dataCSV);

		$header_view->display();
		$profile_view->display();
        $csv_upload_view->display();
		$footer_view->display();
	}

	private function validate() {
		if (!$_REQUEST['login'] || trim($_REQUEST['login']) == '') {
			$this->error[] = "Login is empty";
		}

		if (!$_REQUEST['first_name'] || trim($_REQUEST['first_name']) == '') {
			$this->error[] = "First name is empty";
		}

		if (!$_REQUEST['last_name'] || trim($_REQUEST['last_name']) == '') {
			$this->error[] = "Last name is empty";
		}

		if (!$_REQUEST['email'] || trim($_REQUEST['email']) == '') {
			$this->error[] = "Email is empty";
		} else if (!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error[] = "Email address is invalid";
		}

		$pwd1 = trim($_REQUEST['pwd1']);
		$pwd2 = trim($_REQUEST['pwd2']);

		if ($pwd1 != '' || $pwd2 != '') {
			if ($pwd1 != $pwd2) {
				$this->error[] = "The passwords doesn't match";
			} else if (strlen($pwd1) < 3) {
				$this->error[] = "The pasword is too short (less then 3 characters)";
			}
		}
	}


    //BoltovIgnat
    public function upload() {

        $db = Main::getDB();
        $itogInsert = 0;
        $itogNoValid = 0;
        $filename=$_FILES["users_file"]["tmp_name"];


        if($_FILES["users_file"]["size"] > 0)
        {

            if($file = fopen($filename, "r")){

                while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
                {

                    //validate user
                    if($getData[0] == ""){
                        continue;
                    }
                    $sqlUsers = "SELECT * FROM `users` WHERE `users`.`login` = '".trim($getData[2])."'";

                    $mUsers = $db->fetch_all_array($sqlUsers);

                    if(sizeof($mUsers)){
                        $itogNoValid++;
                    }else {
                        $sql = "INSERT into users (first_name,last_name,login,email,password)  values ('" . trim($getData[0]) . "','" . trim($getData[1]) . "','" . trim($getData[2]) . "','" . trim($getData[3]) . "','" . md5(trim($getData[4])) . "')";
                        $result = $db->query($sql);
                        if ($result) $itogInsert++;
                    }
                }
            }else{
                print_r('ssss');
            }
            fclose($file);

        }
        $_SESSION['itogInsert'] = $itogInsert;
        $_SESSION['itogNoValid'] = $itogNoValid;
        header("Location: /profile");
        exit();
    }

    public function random() {
        $randId= rand();
        $db = Main::getDB();
        $sqlUsers = "SELECT * FROM `users` WHERE `users`.`id` = '".trim(222)."'";

        $mUsers = $db->fetch_all_array($sqlUsers);

        if(empty($mUsers)){
            $options=array(
                "status"=>0,
                "msg"=>'Nobody on id: '+$randId,
            );
            echo json_encode($options);
        }else {
            $options=array(
                "status"=>1,
                "fn"=>$mUsers[0]['first_name'],
                "ln"=>$mUsers[0]['last_name'],
                "id"=>$mUsers[0]['id'],
            );
            echo json_encode($options);
        }


    }

}
