<?php

require_once __DIR__ . "/../php/database.php";
require_once __DIR__ . "/../models/User.php";

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        global $pdo;

        $this->userModel = new User($pdo);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    // ==================================================
    // LOGIN PAGE
    // ==================================================

    public function login(): void
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: ../account.php");
            exit;
        }

        $loginError =
            $_SESSION['login_error'] ?? '';

        unset($_SESSION['login_error']);

        require __DIR__ . "/../views/auth/login.php";
    }


    // ==================================================
    // AUTHENTICATE USER
    // ==================================================

    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../login.php");
            exit;
        }


        $email = trim(
            $_POST['email'] ?? ''
        );

        $password =
            $_POST['password'] ?? '';


        // ----------------------------------------------
        // Validate required fields
        // ----------------------------------------------

        if (
            $email === '' ||
            $password === ''
        ) {
            $_SESSION['login_error'] =
                "Please enter your email and password.";

            header("Location: ../login.php");

            exit;
        }


        // ----------------------------------------------
        // Validate email
        // ----------------------------------------------

        if (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            $_SESSION['login_error'] =
                "Please enter a valid email address.";

            header("Location: ../login.php");

            exit;
        }


        // ----------------------------------------------
        // Find user
        // ----------------------------------------------

        $user =
            $this->userModel->getByEmail(
                $email
            );


        // ----------------------------------------------
        // Verify password
        // ----------------------------------------------

        if (
            !$user ||
            !password_verify(
                $password,
                $user['password_hash']
            )
        ) {
            $_SESSION['login_error'] =
                "Invalid email or password.";

            header("Location: ../login.php");

            exit;
        }


        // ----------------------------------------------
        // Prevent session fixation
        // ----------------------------------------------

        session_regenerate_id(true);


        // ----------------------------------------------
        // Store authenticated user
        // ----------------------------------------------

        $_SESSION['user_id'] =
            (int) $user['user_id'];

        $_SESSION['user_first_name'] =
            $user['first_name'];

        $_SESSION['user_last_name'] =
            $user['last_name'];

        $_SESSION['user_email'] =
            $user['email'];


        // ----------------------------------------------
        // Login successful
        // ----------------------------------------------

        header("Location: ../account.php");

        exit;
    }


    // ==================================================
    // REGISTRATION PAGE
    // ==================================================

    public function register(): void
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: ../account.php");
            exit;
        }

        $registerError =
            $_SESSION['register_error'] ?? '';

        unset($_SESSION['register_error']);

        require __DIR__ . "/../views/auth/register.php";
    }


    // ==================================================
    // CREATE ACCOUNT
    // ==================================================

    public function createAccount(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../register.php");
            exit;
        }


        $firstName =
            trim($_POST['first_name'] ?? '');

        $lastName =
            trim($_POST['last_name'] ?? '');

        $email =
            trim($_POST['email'] ?? '');

        $password =
            $_POST['password'] ?? '';

        $confirmPassword =
            $_POST['confirm_password'] ?? '';


        // ----------------------------------------------
        // Required fields
        // ----------------------------------------------

        if (
            $firstName === '' ||
            $lastName === '' ||
            $email === '' ||
            $password === '' ||
            $confirmPassword === ''
        ) {
            $_SESSION['register_error'] =
                "Please complete all required fields.";

            header("Location: ../register.php");

            exit;
        }


        // ----------------------------------------------
        // Name validation
        // ----------------------------------------------

        if (strlen($firstName) > 50) {
            $_SESSION['register_error'] =
                "First name is too long.";

            header("Location: ../register.php");

            exit;
        }

        if (strlen($lastName) > 50) {
            $_SESSION['register_error'] =
                "Last name is too long.";

            header("Location: ../register.php");

            exit;
        }


        // ----------------------------------------------
        // Email validation
        // ----------------------------------------------

        if (strlen($email) > 150) {
            $_SESSION['register_error'] =
                "Email address is too long.";

            header("Location: ../register.php");

            exit;
        }

        if (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            $_SESSION['register_error'] =
                "Please enter a valid email address.";

            header("Location: ../register.php");

            exit;
        }


        // ----------------------------------------------
        // Password validation
        // ----------------------------------------------

        if (strlen($password) < 8) {
            $_SESSION['register_error'] =
                "Password must be at least 8 characters.";

            header("Location: ../register.php");

            exit;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['register_error'] =
                "Passwords do not match.";

            header("Location: ../register.php");

            exit;
        }


        // ----------------------------------------------
        // Check existing account
        // ----------------------------------------------

        $existingUser =
            $this->userModel->getByEmail(
                $email
            );

        if ($existingUser) {
            $_SESSION['register_error'] =
                "An account with that email already exists.";

            header("Location: ../register.php");

            exit;
        }


        // ----------------------------------------------
        // Hash password
        // ----------------------------------------------

        $passwordHash =
            password_hash(
                $password,
                PASSWORD_DEFAULT
            );


        // ----------------------------------------------
        // Create account
        // ----------------------------------------------

        try {

            $userId =
                $this->userModel->create(
                    $firstName,
                    $lastName,
                    $email,
                    $passwordHash
                );


            // ------------------------------------------
            // Prevent session fixation
            // ------------------------------------------

            session_regenerate_id(true);


            $_SESSION['user_id'] =
                $userId;

            $_SESSION['user_first_name'] =
                $firstName;

            $_SESSION['user_last_name'] =
                $lastName;

            $_SESSION['user_email'] =
                $email;


            header("Location: ../account.php");

            exit;

        } catch (PDOException $e) {

            $_SESSION['register_error'] =
                "Unable to create your account. Please try again.";

            header("Location: ../register.php");

            exit;
        }
    }


    // ==================================================
    // LOGOUT
    // ==================================================

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        // ----------------------------------------------
        // Clear all session data
        // ----------------------------------------------

        $_SESSION = [];


        // ----------------------------------------------
        // Remove session cookie
        // ----------------------------------------------

        if (ini_get("session.use_cookies")) {

            $params =
                session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }


        // ----------------------------------------------
        // Destroy session
        // ----------------------------------------------

        session_destroy();


        // ----------------------------------------------
        // Return to home page
        // ----------------------------------------------

        header("Location: ../index.php");

        exit;
    }
}