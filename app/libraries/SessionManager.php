<?php
class SessionManager
{
    private $regenInterval = 300; // Regenerate ID every 5 min
    private $timeout = 1800; // Inactivity timeout (30 min)

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 1); // HTTPS only
            ini_set('session.use_strict_mode', 1);

            session_start();
        }

        $this->validateSession();
    }

    private function validateSession()
    {
        // If new session, set markers
        if (!isset($_SESSION['ip']) || !isset($_SESSION['ua']) || !isset($_SESSION['created'])) {
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['created'] = time();
            $_SESSION['last_activity'] = time();
        } else {
            // 🚨 Prevent Session Hijacking
            if (
                $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] ||
                $_SESSION['ua'] !== $_SERVER['HTTP_USER_AGENT']
            ) {
                $this->destroy("⚠️ Session Hijacking Detected!");
            }

            // 🚨 Prevent Inactivity abuse
            if (time() - $_SESSION['last_activity'] > $this->timeout) {
                $this->destroy("⚠️ Session Expired, please login again.");
            }

            $_SESSION['last_activity'] = time();

            // 🚨 Prevent Session Fixation
            if (time() - $_SESSION['created'] > $this->regenInterval) {
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }

    public function destroy($message = "Session Ended")
    {
        // Clear session data
        session_unset();
        session_destroy();

        // Optionally store flash message
        $_SESSION = [];
        setMessage('error', '⚠️ Session Expired, please login again.');
                redirect('pages/login'); // redirect to login page
                exit;
        // exit;
    }

}