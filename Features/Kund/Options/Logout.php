<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Logout {
    public function execute() {
        atm_reset_session();
    }
}
function atm_reset_session() {

    // 1. Remove all session variables
    $_SESSION = [];

    // 2. Delete the session cookie (if cookies are used)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,   // Expire far in the past
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // 3. Destroy the session data on the server
    session_destroy();

    // 4. Regenerate a fresh session ID for the next user
    session_write_close();
    session_start();
    session_regenerate_id(true);

    // 5. Redirect to login screen
    header("Location: /Bankomat/Public/Index.php");
    exit;
}