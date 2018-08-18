<?php

namespace app\models;

class AuthorsModel extends Model
{
    public function getAllAuthors()
    {
        $dbPrefix = self::$dbPrefix;

        $authors = self::$builder->table("{$dbPrefix}authors")
            ->fields(['id', 'name'])
            ->groupBy(['id'])
            ->select()
            ->run();

        return $authors;
    }

    public function getAuthorById($id)
    {
        $dbPrefix = self::$dbPrefix;

        $author = self::$builder->table("{$dbPrefix}authors")
            ->fields(['id', 'name'])
            ->where(['id', '=', $id])
            ->select()
            ->run();

        return $author;
    }

    public function addAuthor($name)
    {
        $dbPrefix = self::$dbPrefix;

        return self::$builder->table("{$dbPrefix}authors")
            ->fields(['name'])
            ->values([$name])
            ->insert()
            ->run();
    }

    public function updateAuthor($id, $name)
    {
        $dbPrefix = self::$dbPrefix;

        return self::$builder->table("{$dbPrefix}authors")
            ->fields(['name'])
            ->values([$name])
            ->where(['id', '=', $id])
            ->update()
            ->run();
    }

    public function deleteAuthor($id)
    {
        $dbPrefix = self::$dbPrefix;

        return self::$builder->table("{$dbPrefix}authors")
            ->where(['id', '=', $id])
            ->delete()
            ->run();
    }
}