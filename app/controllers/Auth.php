<?php
//for user login and logout
use Google\Client as GoogleClient;
use Google\Service\Oauth2;

class Auth extends Controller
{
    private $db;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model('UserModel');
        $this->db = new Database();
    }

    public function formRegister()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['email_check']) &&
            $_POST['email_check'] == 1
        ) {
            $email = $_POST['email'];
            // call columnFilter Method from Database.php
            $isUserExist = $this->db->columnFilter('users', 'email', $email);
            if ($isUserExist) {
                echo 'Sorry! email has already taken. Please try another.';
            }
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check user exist
            $email = $_POST['email'];
            // call columnFilter Method from Database.php
            $isUserExist = $this->db->columnFilter('users', 'email', $email);
            if ($isUserExist) {
                setMessage('error', 'This email is already registered !');
                redirect('pages/register');
            } else {
                // Validate entries
                $validation = new UserValidator($_POST);
                $data = $validation->validateForm();
                if (count($data) > 0) {
                    $this->view('pages/register1', $data);
                } else {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $phone = $_POST['phone'];

                    $profile_img = 'default_profile.jpg';
                    $provider_token = bin2hex(random_bytes(50));

                    //Hash Password before saving
                    // $password = base64_encode($password);
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


                    $user = new UserModel();
                    $user->setName($name);
                    $user->setPhone($phone);
                    $user->setEmail($email);
                    $user->setPassword($password);
                    $user->setProviderToken($provider_token);
                    $user->setProfileImg($profile_img);
                    $user->setIsActive(0);
                    $user->setRole(1);
                    $user->setCustomerType('Normal');
                    $user->setCreatedAt(date('Y-m-d H:i:s'));
                    $user->setUpdatedAt(date('Y-m-d H:i:s'));
                    $user->setIsLogin(0);
                    $user->setIsConfirmed(0);


                    $userCreated = $this->db->create('users', $user->toArray());
                    //$userCreated="true";

                    if ($userCreated) {
                        //Instatiate mail
                        $mail = new Mail();

                        $verify_token = URLROOT . '/auth/verify/' . $provider_token;
                        $mail->verifyMail($email, $name, $verify_token);
                        setMessage('success', 'Please check your Mail box !');
                        redirect('pages/login');
                    }
                    redirect('pages/register');
                } // end of validation check
            } // end of user-exist
        }
    }

    public function verify($provider_token)
    {
        $user = $this->db->columnFilter('users', 'token', $provider_token);

        if ($user) {
            $success = $this->db->setLogin($user[0]['id']);

            if ($success) {
                setMessage(
                    'success',
                    'Successfully Verified . Please log in !'
                );
            } else {
                setMessage('error', 'Fail to Verify . Please try again!');
            }
        } else {
            setMessage('error', 'Incrorrect Token . Please try again!');
        }

        redirect('');
    }

    public function login()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password']; // ✅ plain password
                $isLogin = $this->db->loginCheck($email, $password);

                if ($isLogin) {
                    $user = $isLogin; // Already a single row

                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['profile_img'] = $user['profile_img'];
                    // $_SESSION['user_id'] = base64_encode($user['id']);
                    $_SESSION['user_id'] = (int) $user['id']; // ✅ integer
                    $_SESSION['customer_type'] = $user['customer_type'];
                    // var_dump($user);
                    // var_dump($_SESSION); exit;

                    setMessage('id', base64_encode($isLogin['id']));
                    $id = $isLogin['id'];
                    $setLogin = $this->db->setLogin($id);
                    if ($isLogin['role'] == 0) {
                        redirect('movie/dashboard');
                    } elseif ($isLogin['role'] == 1) {
                        redirect('pages/home');
                    } else {
                        // Optional fallback if role is unknown
                        setMessage('error', 'Unknown role.');
                        redirect('pages/login');
                    }
                } else {
                    setMessage('error', 'Login Fail!');
                    redirect('pages/login');
                }

            }
        }
    }

    public function googleLogin()
    {
        // var_dump("ok");//string(2) "ok"
        // exit;
        $client = new GoogleClient();
        $client->setClientId(GOOGLE_CLIENT_ID);
        // var_dump($dd);
        // exit;//Null
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);
        $client->addScope('email');
        $client->addScope('profile');

        $authUrl = $client->createAuthUrl();
        header('Location: ' . $authUrl);
        exit;
    }

    public function googleCallback()
    {
        $client = new GoogleClient();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

            if (isset($token['error'])) {
                setMessage('error', 'Google login failed: ' . $token['error_description']);
                redirect('pages/login');
                exit;
            }

            $client->setAccessToken($token);

            $oauth = new Oauth2($client);
            $google_user = $oauth->userinfo->get();

            $email = $google_user->email;
            $name = $google_user->name;
            $profile_img = $google_user->picture;

            $existingUser = $this->db->columnFilter('users', 'email', $email);
            if ($existingUser) {
                $user = $existingUser;
            } else {
                $provider_token = bin2hex(random_bytes(50));
                $user = [
                    'name' => $name,
                    'email' => $email,
                    'password' => '',
                    'phone' => '',
                    'profile_img' => $profile_img,
                    'provider_token' => $provider_token,
                    'role' => 1,
                    'customer_type' => 'Normal',
                    'is_active' => 1,
                    'is_confirmed' => 1,
                    'is_login' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->db->create('users', $user);
                $user['id'] = $this->db->lastInsertId();
            }

            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_img'] = $user['profile_img'];
            $_SESSION['user_id'] = (int) $user['id'];
            $this->db->setLogin($user['id']);

            // Role ပေါ် မူတည်ပြီး redirect
            if ($user['role'] == 0) {
                redirect('movie/dashboard');
            } else {
                redirect('pages/home');
            }
        } else {
            setMessage('error', 'Login with Google failed.');
            redirect('pages/login');
        }
    }




    // function logout($id)
    // {
    //     // session_start();
    //     // $this->db->unsetLogin(base64_decode($_SESSION['id']));

    //     //$this->db->unsetLogin($this->auth->getAuthId());
    //     $this->db->unsetLogin($id);
    //     redirect('pages/login');
    // }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $id = (int) $_SESSION['user_id'];
            $this->db->unsetLogin($id);
        }

        session_unset();
        session_destroy();

        redirect('pages/login');
    }

    public function forgotPassword()
    {
        $this->view('pages/forgotpass');
    }
    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');

            // Check if the email exists
            $user = $this->db->columnFilter('users', 'email', $email);
            // var_dump($user); exit;
            if ($user) {
                // $_SESSION['email'] = $user['email'];
                $_SESSION['email'] = $email;
                // Generate a 4-digit OTP
                $otp = rand(1000, 9999);
                $_SESSION['otp'] = $otp;

                // Send OTP Email
                $mail = new Mail();
                $mail->sendOtpMail($email, $otp);

                // Redirect to OTP verification page
                $this->view('pages/sendOtp');
            } else {
                setMessage('error', 'Email not found in our system.');
                redirect('auth/forgotPassword');
            }
        } else {
            redirect('auth/forgotPassword');
        }
    }
    public function sendOtp()
    {
        if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
            setMessage('error', 'Please enter your email first.');
            redirect('auth/forgotPassword');
            exit;
        }
        $this->view('pages/sendOtp'); // show OTP form
    }
    public function resendOtp()
    {
        if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
            setMessage('error', 'Please enter your email first.');
            redirect('auth/forgotPassword');
            exit;
        }

        $email = $_SESSION['email'];

        // Generate a new 4-digit OTP
        $otp = rand(1000, 9999);
        $_SESSION['otp'] = $otp;

        // Send OTP Email
        $mail = new Mail();
        $mail->sendOtpMail($email, $otp);

        setMessage('success', 'A new OTP has been sent to your email.');
        redirect('auth/sendOtp'); // redirect to the OTP page
    }



    public function resetPassword()
    {
        $this->view('pages/newPass'); // show OTP form
    }
    public function verifyOtp()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otpInput = implode('', array_map('trim', [
                $_POST['digit1'] ?? '',
                $_POST['digit2'] ?? '',
                $_POST['digit3'] ?? '',
                $_POST['digit4'] ?? ''
            ]));

            if (isset($_SESSION['otp']) && $_SESSION['otp'] == $otpInput) {

                setMessage('success', 'OTP Verified. You can now reset your password.');
                redirect('auth/resetPassword');  // redirect instead of view()
            } else {
                setMessage('error', 'Incorrect OTP. Please try again.');
                redirect('auth/sendOtp');  // redirect to your OTP page route (adjust route accordingly)
            }
        }

    }
    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['con_password'] ?? '');

            if (empty($password) || empty($confirmPassword)) {
                setMessage('error', 'Both password fields are required.');
                redirect('auth/resetPassword');
                return;
            }

            if ($password !== $confirmPassword) {
                setMessage('error', 'Passwords do not match.');
                redirect('auth/resetPassword');
                return;
            }

            if (!isset($_SESSION['email'])) {
                setMessage('error', 'Session expired. Please restart password reset process.');
                redirect('auth/forgotPassword');
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $email = $_SESSION['email'];

            $updated = $this->db->updatePasswordByEmail($email, $hashedPassword);

            if ($updated) {
                unset($_SESSION['otp']);
                unset($_SESSION['email']);
                setMessage('success', 'Password updated successfully. You can now login.');
                redirect('pages/login');
            } else {
                setMessage('error', 'Failed to update password. Try again.');
                redirect('auth/resetPassword');
            }
        }
    }



}
