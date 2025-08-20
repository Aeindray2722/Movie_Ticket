<?php
//for user login and logout
use Google\Client as GoogleClient;
use Google\Service\Oauth2;
require_once __DIR__ . '/../helpers/RateLimiter.php';

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

        // CSRF token generation
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
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

            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('pages/register');
                exit;
            }
            // 2️⃣ reCAPTCHA validation
                $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
                $remoteIp = $_SERVER['REMOTE_ADDR'];
                $secret = GOOGLE_RECAPTCHA_SECRET;

                $verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}&remoteip={$remoteIp}";
                $response = file_get_contents($verifyUrl);
                $responseData = json_decode($response);
                if (!$responseData->success) {
                    setMessage('error', ' Please confirm you are human.');
                    redirect('pages/register');
                    exit;
                }

            // 2️⃣ Continue your existing registration logic
            $email = $_POST['email'];
            $isUserExist = $this->db->columnFilter('users', 'email', $email);

            if ($isUserExist) {
                setMessage('error', 'This email is already registered!');
                redirect('pages/register');
                exit;
            }

            // Validation
            $validation = new UserValidator($_POST);
            $data = $validation->validateForm();

            if (count($data) > 0) {
                $this->view('pages/register1', $data);
                exit;
            }

            // Create user
            $name = $_POST['name'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $phone = $_POST['phone'];
            $profile_img = 'default_profile.jpg';
            $provider_token = bin2hex(random_bytes(50));

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

            if ($userCreated) {
                $mail = new Mail();
                $verify_token = URLROOT . '/auth/verify/' . $provider_token;
                $mail->verifyMail($email, $name, $verify_token);

                setMessage('success', 'Please check your email for verification!');
                redirect('pages/login');
            }
        }
    }


    public function verify($provider_token)
    {
        // $user = $this->db->columnFilter('users', 'token', $provider_token);
        $user = $this->db->columnFilter('users', 'provider_token', $provider_token);

        if ($user) {
            // Case: If result is an array of rows
            if (isset($user[0])) {
                $userId = $user[0]['id'];
            } else {
                // Case: If result is a single row
                $userId = $user['id'];
            }

            $success = $this->db->setLogin($userId);
            if ($success) {
                setMessage(
                    'success',
                    'Successfully Verified . Please log in !'
                );
                redirect('pages/login');
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

                // 1️⃣ CSRF validation
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                    redirect('pages/login');
                    exit;
                }

                // 2️⃣ reCAPTCHA validation
                $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
                $remoteIp = $_SERVER['REMOTE_ADDR'];
                $secret = GOOGLE_RECAPTCHA_SECRET;

                $verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}&remoteip={$remoteIp}";
                $response = file_get_contents($verifyUrl);
                $responseData = json_decode($response);

                if (!$responseData->success) {
                    // var_dump("ok"); exit;
                    setMessage('error', ' Please confirm you are human.');
                    redirect('pages/login');
                    exit;
                }

                // 3️⃣ Rate limiter
                $email = $_POST['email'];
                $ip = $_SERVER['REMOTE_ADDR'];
                $limiter = new RateLimiter($ip . '_' . $email, 5, 60);
                if ($limiter->tooManyAttempts()) {
                    setMessage('error', 'Too many login attempts. Try again in ' . $limiter->availableIn() . ' seconds.');
                    redirect('pages/login');
                    exit;
                }

                // 4️⃣ Login logic
                $password = $_POST['password'];
                $isLogin = $this->db->loginCheck($email, $password);

                if ($isLogin) {
                    $user = $isLogin;
                    // Check if the account is confirmed
                    if ($user['is_confirmed'] == 0) {
                        setMessage('error', 'Please verify your email address first!');
                        redirect('pages/login');
                        return;
                    }
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['profile_img'] = $user['profile_img'];
                    $_SESSION['user_id'] = (int) $user['id'];
                    $_SESSION['customer_type'] = $user['customer_type'];

                    $this->db->setLogin($user['id']);

                    if ($user['role'] == 0) {
                        redirect('movie/dashboard');
                    } else {
                        redirect('pages/home');
                    }
                } else {
                    $limiter->hit();
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
    public function githubLogin()
    {
        $clientId = GITHUB_CLIENT_ID;
        $redirectUri = GITHUB_REDIRECT_URI;

        $authUrl = "https://github.com/login/oauth/authorize?client_id={$clientId}&redirect_uri={$redirectUri}&scope=user:email";
        header("Location: $authUrl");
        exit;
    }

    public function githubCallback()
    {
        if (!isset($_GET['code'])) {
            setMessage('error', 'GitHub login failed.');
            redirect('pages/login');
            return;
        }

        $code = $_GET['code'];

        // 1️⃣ Exchange code for access token
        $tokenUrl = "https://github.com/login/oauth/access_token";
        $postFields = [
            'client_id' => GITHUB_CLIENT_ID,
            'client_secret' => GITHUB_CLIENT_SECRET,
            'code' => $code,
            'redirect_uri' => GITHUB_REDIRECT_URI,
        ];

        $ch = curl_init($tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        $response = curl_exec($ch);
        curl_close($ch);

        $tokenData = json_decode($response, true);

        if (!isset($tokenData['access_token'])) {
            setMessage('error', 'GitHub token exchange failed.');
            redirect('pages/login');
            return;
        }

        $accessToken = $tokenData['access_token'];

        // 2️⃣ Get user info
        $ch = curl_init("https://api.github.com/user");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: token {$accessToken}",
            "User-Agent: MovieFlow-App"
        ]);
        $userResponse = curl_exec($ch);
        curl_close($ch);

        $githubUser = json_decode($userResponse, true);

        if (!isset($githubUser['id'])) {
            setMessage('error', 'Failed to fetch GitHub user info.');
            redirect('pages/login');
            return;
        }

        $email = $githubUser['email'] ?? ($githubUser['login'] . "@github.com");
        $name = $githubUser['name'] ?? $githubUser['login'];
        $profile_img = $githubUser['avatar_url'] ?? 'default_profile.jpg';

        // 3️⃣ Check if user exists
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

        // 4️⃣ Set session
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['profile_img'] = $user['profile_img'];
        $_SESSION['user_id'] = (int) $user['id'];
        $this->db->setLogin($user['id']);

        if ($user['role'] == 0) {
            redirect('movie/dashboard');
        } else {
            redirect('pages/home');
        }
    }

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
            // 1️⃣ CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('auth/forgotPassword');
                exit;
            }
            $email = trim($_POST['email'] ?? '');
            $ip = $_SERVER['REMOTE_ADDR'];
            $limiter = new RateLimiter($ip . '_otp', 3, 60); // max 3 OTP per minute

            if ($limiter->tooManyAttempts()) {
                setMessage('error', 'Too many OTP requests. Please wait ' . $limiter->availableIn() . ' seconds.');
                redirect('auth/sendOtp');
                exit;
            }

            $limiter->hit(); // count OTP request


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
            redirect('auth/sendOtp');
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

            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('auth/sendOtp');
                exit;
            }
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

            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setMessage('error', 'Invalid CSRF token. Please refresh the page.');
                redirect('auth/resetPassword');
                exit;
            }
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
