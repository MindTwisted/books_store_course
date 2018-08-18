<?php

namespace app\models;

class GenresModel extends Model
{
    public function getAllGenres()
    {
        $dbPrefix = self::$dbPrefix;

        $genres = self::$builder->table("{$dbPrefix}genres")
            ->fields(['id', 'name'])
            ->groupBy(['id'])
            ->select()
            ->run();

        return $genres;
    }

    public function getGenreById($id)
    {
        $dbPrefix = self::$dbPrefix;

        $genre = self::$builder->table("{$dbPrefix}genres")
            ->fields(['id', 'name'])
            ->where(['id', '=', $id])
            ->select()
            ->run();

        return $genre;
    }

    public function addGenre($name)
    {
        $dbPrefix = self::$dbPrefix;

        return self::$builder->table("{$dbPrefix}genres")
            ->fields(['name'])
            ->values([$name])
            ->insert()
            ->run();
    }

    public function updateGenre($id, $name)
    {
        $dbPrefix = self::$dbPrefix;

        return self::$builder->table("{$dbPrefix}genres")
            ->fields(['name'])
            ->values([$name])
            ->where(['id', '=', $id])
            ->update()
            ->run();
    }

    public function deleteGenre($id)
    {
        $dbPrefix = self::$dbPrefix;

        return self::$builder->table("{$dbPrefix}genres")
            ->where(['id', '=', $id])
            ->delete()
            ->run();
    }
}