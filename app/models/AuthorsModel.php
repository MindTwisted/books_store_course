<?php

namespace app\models;

class AuthorsModel extends Model
{
    public function getAllAuthors()
    {
        $authors = $this->queryBuilder->table("{$this->dbPrefix}authors")
            ->fields(['id', 'name'])
            ->groupBy(['id'])
            ->select()
            ->run();

        return $authors;
    }

    public function getAuthorById($id)
    {
        $author = $this->queryBuilder->table("{$this->dbPrefix}authors")
            ->fields(['id', 'name'])
            ->where(['id', '=', $id])
            ->select()
            ->run();

        return $author;
    }

    public function addAuthor($name)
    {
        return $this->queryBuilder->table("{$this->dbPrefix}authors")
            ->fields(['name'])
            ->values([$name])
            ->insert()
            ->run();
    }
}