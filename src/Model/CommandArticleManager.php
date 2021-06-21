<?php

namespace App\Model;

class CommandArticleManager extends AbstractManager
{

    public const TABLE = 'command_article';

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
        " (`command_id`,`article_id`,`qty`) VALUES (:command_id, :article_id, :qty)");
        $statement->bindValue('command_id', $command['command_id'], \PDO::PARAM_INT);
        $statement->bindValue('article_id', $command['article_id'], \PDO::PARAM_INT);
        $statement->bindValue('qty', $command['qty'], \PDO::PARAM_INT);

        $statement->execute(); {
            return (int) $this->pdo->lastInsertId();
        }
    }
}
