<?php

namespace App\Model;

class CommandManager extends AbstractManager
{
    public const TABLE = 'command';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $command): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`created_at`,`total`,`user_id`,`address`)
            VALUES (:created_at, :total, :price, :address)");
        $statement->bindValue('created_at', $command['created_at'], \PDO::PARAM_STR);
        $statement->bindValue('total', $command['total'], \PDO::PARAM_INT);
        $statement->bindValue('price', $command['price'], \PDO::PARAM_INT);
        $statement->bindValue('address', $command['address'], \PDO::PARAM_STR);


        $statement->execute(); {
            return (int) $this->pdo->lastInsertId();
        }
    }
}
