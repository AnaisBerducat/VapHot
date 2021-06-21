<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\CategoryManager;
use App\Model\CommandManager;
use App\Model\ContactManager;
use App\Model\UserManager;

class AdminController extends AbstractController
{
    /**
     * ROUTE /admin/dashboard

     */
    public function dashboard()
    {
        if (isset($_SESSION['user']) && $_SESSION['user']['is_admin'] == 1) {
            return $this->twig->render('Admin/index.html.twig');
        } else {
            header('Location: /');
        }
    }

    /**
     * ROUTE /home/contacts
     */
    public function contacts()
    {
        $contactManager = new ContactManager();
        $contacts = $contactManager->selectAll();
        return $this->twig->render('Admin/contact.html.twig', [
            'contacts' => $contacts
        ]);
    }

    /**
     * ROUTE /home/users
     */
    public function users()
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll();
        return $this->twig->render('Admin/users.html.twig', [
            'users' => $users
        ]);
    }
    /**
     * ROUTE /home/commands
     */
    public function commands()
    {
        $commandManager = new CommandManager();
        $commands = $commandManager->selectAll();
        return $this->twig->render('Admin/commands.html.twig', [
            'commands' => $commands
        ]);
    }
    /**
     * ROUTE /admin/products
     */
    public function products()
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll();
        return $this->twig->render('Admin/products.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * ROUTE /admin/show/{{param id article}}
     */
    public function show(int $id)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->selectOneById($id);
        return $this->twig->render('Admin/product.html.twig', [
            'article' => $article
        ]);
    }
    /**
     * ROUTE /admin/categories
     */
    public function categories()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        return $this->twig->render('Admin/categories.html.twig', [
            'categories' => $categories
        ]);
    }

        /**
     * ROUTE /admin/addCategories
     */
    public function addCategories()
    {
        $errors = [];
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['name'])) {
                    $category = [
                    'name' => $_POST['name'],
                    ];
                    $categoryManager->insert($category);
                    header('Location: /admin/categories');
            } else {
                $errors[] = "All fields are required";
            }
        }
        return $this->twig->render('Admin/category/form.html.twig', ['errors' => $errors, 'categories' => $categories]);
    }

    /**
     * ROUTE /admin/deleteCategories
     */
    public function deleteCategories(int $id)
    {
        $categoryManager = new CategoryManager();
        $categoryManager->delete($id);
        header('Location: /admin/categories');
    }

    /**
     * ROUTE /admin/editCategories
     */
    public function editCategories(int $id)
    {
        $errors = [];
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['name'])) {
                $categories = [
                    'id' => $id,
                    'name' => $_POST['name'],
                ];
                $categoryManager->update($categories);
                header('Location: /admin/categories');
            } else {
                $errors[] = "All fields are required";
            }
        }
        return $this->twig->render('Admin/category/form.html.twig', ['categories' => $categories, 'errors' => $errors]);
    }



    /**
     * ROUTE /admin/addProduct
     */
    public function addProduct()
    {
        $errors = [];
        $articleManager = new ArticleManager();
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['price']) && !empty($_POST['qty']) && !empty($_POST['image'])) {
                    $article = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'price' => $_POST['price'],
                    'qty' => $_POST['qty'],
                    'image' => $_POST['image'],
                    'category_id' => $_POST['category_id'],
                    ];
                    $articleManager->insert($article);
                    header('Location: /admin/products');
            } else {
                $errors[] = "All fields are required";
            }
        }
        return $this->twig->render('Admin/product/form.html.twig', ['errors' => $errors, 'categories' => $categories]);
    }
    /**
     * ROUTE /admin/deleteProduct
     */
    public function deleteProduct(int $id)
    {
        $articleManager = new ArticleManager();
        $articleManager->delete($id);
        header('Location: /admin/products');
    }
    /**
     * ROUTE /admin/editProduct
     */
    public function editProduct(int $id)
    {
        $errors = [];
        $articleManager = new ArticleManager();
        $article = $articleManager->selectOneById($id);
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['price']) && !empty($_POST['qty']) && !empty($_POST['image'])) {
                $article = [
                    'id' => $id,
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'price' => $_POST['price'],
                    'qty' => $_POST['qty'],
                    'image' => $_POST['image'],
                    'category_id' => $_POST['category_id'],
                ];
                $articleManager->update($article);
                header('Location: /admin/products');
            } else {
                $errors[] = "All fields are required";
            }
        }
        return $this->twig->render('Admin/product/form.html.twig', ['article' => $article,
            'errors' => $errors, 'categories' => $categories]);
    }

    public function deleteContact(int $id)
    {
        if (isset($_SESSION['user']) && $_SESSION['user']['is_admin'] == 1) {
            $contactManager = new ContactManager();
            $contactManager->delete($id);
            header('Location:/admin/contacts');
        } else {
            header('Location: /');
        }
    }

    public function deleteUser(int $id)
    {
        $userManager = new UserManager();
        $userManager->delete($id);
        header('Location: /admin/users');
    }

}
