<?php

namespace App\Controller;

use App\Model\UserManager;

class SecurityController extends AbstractController
{
    /**
     * ROUTE /security/register
     */
    public function register()
    {
        $userManager = new UserManager();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (
                !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password2'])
                && !empty($_POST['firstname']) && !empty($_POST['lastname'])
            ) {
                $user = $userManager->searchUser($_POST['email']);
                if (!$user) {
                    if ($_POST['password'] === $_POST['password2']) {
                        if (strlen($_POST['password']) >= 8) {
                            $user = [
                                "email" => $_POST['email'],
                                "password" => md5($_POST['password']),
                                "firstname" => $_POST['firstname'],
                                "lastname" => $_POST['lastname'],
                                'is_admin' => 0,
                            ];
                            $id = $userManager->insert($user);
                            if ($id) {
                                $_SESSION['user'] = $userManager->selectOneById($id);
                                header('Location: /');
                            }
                        } else {
                            $errors[] = "Password must contain at least 8 characters !";
                        }
                    } else {
                        $errors[] = "The passwords doesn't match !";
                    }
                } else {
                    $errors[] = "Email exist !";
                }
            } else {
                $errors[] = "All fields are required !";
            }
        }
        return $this->twig->render('Security/register.html.twig', ['errors' => $errors]);
    }

    /**
     * ROUTE /security/login
     */
    public function login()
    {
        $userManager = new UserManager();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $user = $userManager->searchUser($_POST['email']);
                if ($user) {
                    if ($user['password'] === md5($_POST['password'])) {
                        $_SESSION['user'] = $user;
                        header('Location: /');
                    } else {
                        $errors[] = "Invalid password !";
                    }
                } else {
                    $errors[] = "Email doesn't exist !";
                }
            }
        } else {
            $errors[] = "All fields are required !";
        }
        return $this->twig->render('Security/login.html.twig', ['errors' => $errors]);
    }

    /**
     * ROUTE /security/logout
     */
    public function logout()
    {
        session_destroy();
        header('Location: /');
    }
}
