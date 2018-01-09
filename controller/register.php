<?phpclass Controller_register extends Controller {	public function index() {		$user_model = Controller::loadModel("user");		$header_view = new View("header");		$register_view = new View("register");		$footer_view = new View("footer");		if (isset($_REQUEST['login'])) {			$this->validate();			$userInfo = array(				'login' => $_REQUEST['login'],				'first_name' => $_REQUEST['first_name'],				'last_name' => $_REQUEST['last_name'],				'email' => $_REQUEST['email'],				'password' => md5($_REQUEST['pwd1']),				'role' => 0);			if (empty($this->error)) {				if ($user_model->addNewUser($userInfo)) {					$register_view = new View("success");				} else {					$this->error[] = $user_model->getLastError();				}			}		}		$header['title'] = "Registration";		$data['result_text'] = "Registration are success.";		$data['action'] = "/register";		$data['error'] = $this->error;		$data['login'] = isset($userInfo['login']) ? $userInfo['login'] : '';		$data['first_name'] = isset($userInfo['first_name']) ? $userInfo['first_name'] : '';		$data['last_name'] = isset($userInfo['last_name']) ? $userInfo['last_name'] : '';		$data['email'] = isset($userInfo['email']) ? $userInfo['email'] : '';		$data['submit_button'] = 'Register';		$data['link_url'] = '/login';		$data['link_text'] = 'Login';		$header['title'] = "Registration";		$header_view->setData($header);		$register_view->setData($data);		$header_view->display();		$register_view->display();		$footer_view->display();	}	private function validate() {		if (!$_REQUEST['login'] || trim($_REQUEST['login']) == '') {			$this->error[] = "Login is empty";		}		if (!$_REQUEST['first_name'] || trim($_REQUEST['first_name']) == '') {			$this->error[] = "First name is empty";		}		if (!$_REQUEST['last_name'] || trim($_REQUEST['last_name']) == '') {			$this->error[] = "Last name is empty";		}		if (!$_REQUEST['email'] || trim($_REQUEST['email']) == '') {			$this->error[] = "Email is empty";		} else if (!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {			$this->error[] = "Email address is invalid";		}		$pwd1 = trim($_REQUEST['pwd1']);		$pwd2 = trim($_REQUEST['pwd2']);		if ($pwd1 != $pwd2) {			$this->error[] = "The passwords doesn't match";		} else if (strlen($pwd1) < 3) {			$this->error[] = "The pasword is too short (less then 3 characters)";		}	}}