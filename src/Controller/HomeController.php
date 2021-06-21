<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\CommandArticleManager;
use App\Model\CommandManager;
use App\Model\ContactManager;
use App\Model\WishlistManager;
use PDepend\TextUI\Command;

class HomeController extends AbstractController
{
    /**
     * ROUTE /
     */
    public function index()
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll();
        return $this->twig->render('Home/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * ROUTE /home/contact
     */
    public function contact()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (
                !empty($_POST['firstname'])
                && !empty($_POST['lastname'])
                && !empty($_POST['subject']) && !empty($_POST['message'])
            ) {
                $contactManager = new ContactManager();
                $contact = [
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'subject' => $_POST['subject'],
                    'message' => $_POST['message'],
                ];
                $contactManager->insert($contact);
                header('Location: /home/success');
            } else {
                $errors[] = "All fields are required !";
            }
        }
        return $this->twig->render('Home/contact.html.twig', ['errors' => $errors]);
    }

    /**
     * ROUTE /home/products
     */
    public function products()
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll();
        return $this->twig->render('Home/products.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * ROUTE /home/show/{{param id article}}
     */
    public function show(int $id)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->selectOneById($id);
        return $this->twig->render('Home/product.html.twig', [
            'article' => $article
        ]);
    }

        /**
     * ROUTE /home/like/{{param id article}}
     */
    public function like(int $idProduct)
    {
        $wishManager = new WishlistManager();
        $isLiked = $wishManager->isLikedByUser($idProduct, $_SESSION['user']['id']);
        if (!$isLiked) {
            $wish = [
                'user_id' => $_SESSION['user']['id'],
                'article_id' => $idProduct
                ];
            $wishManager->insert($wish);
        }
        header('Location: /home/products');
    }

        /**
     * ROUTE /home/dislike/{{param id article}}
     */
    public function dislike(int $idWish)
    {
            $wishManager = new WishlistManager();
            $idWish = intval($idWish);
            $wishManager->delete($idWish);
            header('Location:/home/account');
    }

    /**
     * ROUTE /home/account
     */
    public function account()
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            $wishManager = new WishlistManager();
            $articleManager = new ArticleManager();
            //wishlist article_id
            $wishlist = $wishManager->getWishlistByUser($_SESSION['user']['id']);
            $result = [];
            foreach ($wishlist as $wish) {
                $article = $articleManager->selectOneById($wish['article_id']);
                $result[] = ["id" => $wish["id"], "article" => $article];
            }
            return $this->twig->render('User/account.html.twig', [
                'wishlist' => $result
            ]);
        }
        header('Location: /security/login');
    }

    /**
     * ROUTE /home/success
     */
    public function success()
    {
        return $this->twig->render('Home/success.html.twig');
    }
    /**
     * ROUTE /home/cart
     */

    public function cart()
    {
        $cartInfos = $this->getCartInfos();
        return $this->twig->render('Home/cart.html.twig', [
            'cart' => $cartInfos
        ]);
    }

    /**
     * ROUTE /home/AddToCart
     */
    public function addToCart(int $idArticle)
    {
        if (!empty($_SESSION['cart'][$idArticle])) {
            $_SESSION['cart'][$idArticle] ++;
        } else {
            $_SESSION['cart'][$idArticle] = 1;
        }
        header('Location: /home/products');
    }
    public function deleteFromCart(int $idArticle)
    {
        $cart = $_SESSION['cart'];
        if (!empty($cart[$idArticle])) {
            unset($cart[$idArticle]);
        }
        $_SESSION['cart'] = $cart;
        header('Location: /home/cart');
    }

    public function getCartInfos()
    {
        if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $cartInfos = [];
            $articleManager = new ArticleManager();
            foreach ($cart as $idArticle => $qty) {
                $article = $articleManager->selectOneById($idArticle);
                $article['qty'] = $qty;
                $cartInfos[] = $article;
            }
            return $cartInfos;
        }
        return false;
    }

    public function searchByTitle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $articleManager = new ArticleManager();
            $articles = $articleManager->getByTitle($_POST['title']);
            return $this->twig->render('Home/products.html.twig', ['articles' => $articles]);
        }
    }

    public function totalCart()
    {
        $total = 0;
        if ($this->getCartInfos() != false) {
            foreach ($this->getCartInfos() as $article) {
                $total += $article['price'] * $article['qty'];
            }
            return $total;
        }
        return $total;
    }

    public function order()
    {
        $orderManager = new CommandManager();
        $orderArticleManager = new CommandArticleManager();
        $articleManager = new ArticleManager();
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!empty($_POST['address'])) {
                $order = [
                    'created_at' => date('y-m-d'),
                    'total' => $this->totalCart(),
                    'user_id' => $_SESSION['user']['id'],
                    'address' => $_POST['address'],
                ];
                $idOrder = $orderManager->insert($order);
                
                if ($idOrder) {
                    foreach($_SESSION['cart'] as $idArticle => $qty) {
                        $newLineInTickets = [
                            'order_id' => $idOrder,
                            'article_id' => $idArticle,
                            'qty' => $qty,
                        ];
                        $orderArticleManager->insert($newLineInTickets);
                    }
                    unset($_SESSION['cart']);
                    header('Location: /');
                }
            }
        }
        return $this->twig->render('Home/order.html.twig');
    }
}
