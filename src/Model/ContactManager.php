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
class ContactManager extends AbstractManager
{
    public const TABLE = 'contact';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

/**
     * @param array $contact
     * @return int
     */
    public function insert(array $contact): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
        " (`firstname`,`lastname`,`subject`,`message`)
        VALUES (:firstname, :lastname, :subject, :message)");
        $statement->bindValue('firstname', $contact['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $contact['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('subject', $contact['subject'], \PDO::PARAM_STR);
        $statement->bindValue('message', $contact['message'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
