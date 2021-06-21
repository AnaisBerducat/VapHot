<?php

/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class ArticleManager extends AbstractManager
{
    public const TABLE = 'article';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $article
     * @return int
     */
    public function insert(array $article): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
        " (`title`,`description`,`price`,`image`,`qty`,`category_id`)
        VALUES (:title, :description, :price, :image, :qty, :category_id)");
        $statement->bindValue('title', $article['title'], \PDO::PARAM_STR);
        $statement->bindValue('description', $article['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $article['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $article['image'], \PDO::PARAM_STR);
        $statement->bindValue('qty', $article['qty'], \PDO::PARAM_INT);
        $statement->bindValue('category_id', $article['category_id'], \PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * @param array $article
     * @return bool
     */
    public function update(array $article): bool
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
        " SET `title` = :title, `description` = :description, `price` = :price,
        `image` = :image, `qty` = :qty, `category_id` = :category_id
        WHERE id=:id");
        $statement->bindValue('id', $article['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $article['title'], \PDO::PARAM_STR);
        $statement->bindValue('description', $article['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $article['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $article['image'], \PDO::PARAM_STR);
        $statement->bindValue('qty', $article['qty'], \PDO::PARAM_INT);
        $statement->bindValue('category_id', $article['category_id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * @param array $article
     * @return bool
     */
    public function updateQty(array $article, $qty): bool
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
        " SET `qty` = :qty WHERE id=:id");
        $statement->bindValue('id', $article['id'], \PDO::PARAM_INT);
        $statement->bindValue('qty', $qty, \PDO::PARAM_INT);
        return $statement->execute();
    }

    public function getByTitle(string $title)
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE .
        " WHERE title LIKE :title ");
        $statement->bindValue('title', $title . '%', \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}
