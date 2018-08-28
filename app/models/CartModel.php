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
        $separator = '---';

        self::$builder->raw('SET SESSION group_concat_max_len = 1000000');

        $cart = self::$builder->table($cartTable)
                    ->join($booksTable, [$cartTable.'.book_id', $booksTable.'.id'])
                    ->fields([
                        $cartTable.'.id',
                        'count',
                        "GROUP_CONCAT({$booksTable}.id, '$separator', title, '$separator', description, '$separator', price, '$separator', discount) AS book"
                    ])
                    ->where(['user_id', '=', $userId])
                    ->groupBy([$cartTable.'.id', 'count'])
                    ->select()
                    ->run();

        $cart = array_map(function($item) use ($separator) {
            $item['book'] = explode($separator, $item['book']);

            $book = [
                'id' => $item['book'][0],
                'title' => $item['book'][1],
                'description' => $item['book'][2],
                'price' => $item['book'][3],
                'discount' => $item['book'][4]
            ];

            $item['book'] = $book;

            return $item;
        }, $cart);

        return $cart;           
    }

    public function addToCart($userId, $bookId, $count)
    {
        $dbPrefix = self::$dbPrefix;

        $cart = self::getRowFromCart($userId, $bookId);

        if (count($cart) > 0)
        {
            return false;
        }

        return self::$builder->table("{$dbPrefix}cart")
                    ->fields(['user_id', 'book_id', 'count'])
                    ->values([$userId, $bookId, $count])
                    ->insert()
                    ->run();
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