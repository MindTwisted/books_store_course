<?php

namespace app\models;

class GenresModel extends Model
{
    public function getAllGenres()
    {
        $dbPrefix = $this->getDbPrefix();

        $genres = $this->queryBuilder->table("{$dbPrefix}genres")
            ->fields(['id', 'name'])
            ->groupBy(['id'])
            ->select()
            ->run();

        return $genres;
    }

    public function getGenreById($id)
    {
        $dbPrefix = $this->getDbPrefix();

        $genre = $this->queryBuilder->table("{$dbPrefix}genres")
            ->fields(['id', 'name'])
            ->where(['id', '=', $id])
            ->select()
            ->run();

        return $genre;
    }

    public function addGenre($name)
    {
        $dbPrefix = $this->getDbPrefix();

        return $this->queryBuilder->table("{$dbPrefix}genres")
            ->fields(['name'])
            ->values([$name])
            ->insert()
            ->run();
    }

    public function updateGenre($id, $name)
    {
        $dbPrefix = $this->getDbPrefix();

        return $this->queryBuilder->table("{$dbPrefix}genres")
            ->fields(['name'])
            ->values([$name])
            ->where(['id', '=', $id])
            ->update()
            ->run();
    }

    public function deleteGenre($id)
    {
        $dbPrefix = $this->getDbPrefix();

        return $this->queryBuilder->table("{$dbPrefix}genres")
            ->where(['id', '=', $id])
            ->delete()
            ->run();
    }
}