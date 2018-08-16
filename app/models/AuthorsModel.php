<?php

namespace app\models;

class AuthorsModel extends Model
{
    public function getAllAuthors()
    {
        $dbPrefix = $this->getDbPrefix();

        $authors = $this->queryBuilder->table("{$dbPrefix}authors")
            ->fields(['id', 'name'])
            ->groupBy(['id'])
            ->select()
            ->run();

        return $authors;
    }

    public function getAuthorById($id)
    {
        $dbPrefix = $this->getDbPrefix();

        $author = $this->queryBuilder->table("{$dbPrefix}authors")
            ->fields(['id', 'name'])
            ->where(['id', '=', $id])
            ->select()
            ->run();

        return $author;
    }

    public function addAuthor($name)
    {
        $dbPrefix = $this->getDbPrefix();

        return $this->queryBuilder->table("{$dbPrefix}authors")
            ->fields(['name'])
            ->values([$name])
            ->insert()
            ->run();
    }

    public function updateAuthor($id, $name)
    {
        $dbPrefix = $this->getDbPrefix();

        return $this->queryBuilder->table("{$dbPrefix}authors")
            ->fields(['name'])
            ->values([$name])
            ->where(['id', '=', $id])
            ->update()
            ->run();
    }

    public function deleteAuthor($id)
    {
        $dbPrefix = $this->getDbPrefix();

        return $this->queryBuilder->table("{$dbPrefix}authors")
            ->where(['id', '=', $id])
            ->delete()
            ->run();
    }
}