<?php

namespace app\models;

class CartModel extends Model
{
    private function getRowFromCart($userId, $bookId)
    {
        $dbPrefix = self::$dbPrefix;

        $cart = self::$builder->table("{$dbPrefix}cart")
                    ->fields(['*'])
                    ->where(['user_id', '=', $userId])
                    ->andWhere(['book_id', '=', $bookId])
                    ->limit(1)
                    ->select()
                    ->run();

        return $cart;
    }

    public function getUsersCart($userId)
    {
        $dbPrefix = self::$dbPrefix;

        $cartTable = "{$dbPrefix}cart";
        $booksTable = "{$dbPrefix}books";

        return self::$builder->table($cartTable)
                    ->join($booksTable, [$cartTable.'.book_id', $booksTable.'.id'])
                    ->fields(['count', 'title', 'description', 'image_url', 'price', 'discount'])
                    ->where(['user_id', '=', $userId])
                    ->select()
                    ->run();
    }

    public function addToCart($userId, $bookId, $count)
    {
        $dbPrefix = self::$dbPrefix;

        $cart = self::getRowFromCart($userId, $bookId);

        if (count($cart) > 0)
        {
            return false;
        }

        self::$builder->table("{$dbPrefix}cart")
            ->fields(['user_id', 'book_id', 'count'])
            ->values([$userId, $bookId, $count])
            ->insert()
            ->run();

        return true;
    }

    public function updateInCart($userId, $bookId, $count)
    {
        $dbPrefix = self::$dbPrefix;

        $cart = self::getRowFromCart($userId, $bookId);

        if (count($cart) === 0)
        {
            return false;
        }

        self::$builder->table("{$dbPrefix}cart")
            ->fields(['count'])
            ->values([$count])
            ->where(['user_id', '=', $userId])
            ->andWhere(['book_id', '=', $bookId])
            ->update()
            ->run();

        return true;
    }

    public function deleteFromCart($userId, $bookId)
    {
        $dbPrefix = self::$dbPrefix;

        $cart = self::getRowFromCart($userId, $bookId);

        if (count($cart) === 0)
        {
            return false;
        }

        self::$builder->table("{$dbPrefix}cart")
            ->where(['user_id', '=', $userId])
            ->andWhere(['book_id', '=', $bookId])
            ->delete()
            ->run();

        return true;
    }
}