<?php

namespace app\models;

class OrdersModel extends Model
{
    public function getOrders($orderId = null, $userId = null, $sortRules = [])
    {
        $dbPrefix = self::$dbPrefix;
        $separator = '---';

        $whereClause = [];

        if (null !== $orderId)
        {
            $whereClause[] = ["{$dbPrefix}orders.id", '=', $orderId];
        }

        if (null !== $userId)
        {
            $whereClause[] = ["{$dbPrefix}orders.user_id", '=', $userId];
        }

        $orders = self::$builder->table("{$dbPrefix}orders")
                        ->join(
                            "{$dbPrefix}users",
                            ["{$dbPrefix}orders.user_id", "{$dbPrefix}users.id"]
                        )
                        ->join(
                            "{$dbPrefix}order_details",
                            ["{$dbPrefix}order_details.order_id", "{$dbPrefix}orders.id"]
                        )
                        ->join(
                            "{$dbPrefix}payment_types",
                            ["{$dbPrefix}orders.payment_type_id", "{$dbPrefix}payment_types.id"]
                        )
                        ->fields([
                            "{$dbPrefix}orders.id",
                            'status',
                            "{$dbPrefix}payment_types.name AS payment_type",
                            'total_discount',
                            'total_price',
                            'created_at',
                            "GROUP_CONCAT(DISTINCT {$dbPrefix}users.id, '$separator', {$dbPrefix}users.name, '$separator', email) AS user",
                            "GROUP_CONCAT(DISTINCT book_id, '$separator', book_title, '$separator', book_count, '$separator', book_price, '$separator', book_discount) AS books"
                        ])
                        ->where(['1', '=', '1']);
        
        if (count($whereClause) > 0)
        {
            $orders = $orders->andWhere(...$whereClause);
        }

        if (count($sortRules) > 0)
        {
            $orders = $orders->orderBy(...$sortRules);
        }

        $orders = $orders->groupBy(['id'])
                         ->select()
                         ->run();

        $orders = array_map(function($order) use ($separator) {
            $user = explode($separator, $order['user']);
            $user = [
                'id' => $user[0],
                'name' => $user[1],
                'email' => $user[2]
            ];

            $books = explode(',', $order['books']);
            $books = array_map(function($book) use ($separator) {
                $book = explode($separator, $book);
                $book = [
                    'id' => $book[0],
                    'title' => $book[1],
                    'count' => $book[2],
                    'price' => $book[3],
                    'discount' => $book[4]
                ];

                return $book;
            }, $books);

            $order['user'] = $user;
            $order['books'] = $books;

            return $order;
        }, $orders);

        return $orders;
    }

    public function addOrder($user, $cart, $paymentType)
    {
        $dbPrefix = self::$dbPrefix;

        $totalDiscount = 0;
        $totalPrice = 0;

        $orderDetailsValues = [];

        foreach ($cart as $item)
        {
            $booksCount = +$item['count'];
            $bookFullPrice = +$item['book']['price'];

            $totalDiscountPercent = $user['discount'] + $item['book']['discount'];
            $totalDiscountPercent = $totalDiscountPercent > MAX_DISCOUNT ? +MAX_DISCOUNT : +$totalDiscountPercent;

            $bookDiscount = round($bookFullPrice * ($totalDiscountPercent / 100), 2);
            $bookPriceWithDiscount = round($bookFullPrice - $bookDiscount, 2);

            $totalDiscount += $bookDiscount * $booksCount;
            $totalPrice += $bookPriceWithDiscount * $booksCount;

            $orderDetailsValues[] = [
                $item['book']['id'],
                $item['book']['title'],
                $item['count'],
                $bookPriceWithDiscount,
                $bookDiscount
            ];
        }

        $orderId = self::$builder->table("{$dbPrefix}orders")
                        ->fields(['user_id', 'payment_type_id', 'total_discount', 'total_price'])
                        ->values([$user['id'], $paymentType, $totalDiscount, $totalPrice])
                        ->insert()
                        ->run();

        $orderDetailsValues = array_map(function($value) use ($orderId) {
            $value[] = $orderId;

            return $value;
        }, $orderDetailsValues);

        self::$builder->table("{$dbPrefix}order_details")
            ->fields(['book_id', 'book_title', 'book_count', 'book_price', 'book_discount', 'order_id'])
            ->values(...$orderDetailsValues)
            ->insert()
            ->run();
            
        self::$builder->table("{$dbPrefix}cart")
            ->where(['user_id', '=', $user['id']])
            ->delete()
            ->run();

        return $orderId;
    }

    public function updateOrder($id, $status)
    {
        $dbPrefix = self::$dbPrefix;

        self::$builder->table("{$dbPrefix}orders")
            ->fields(['status'])
            ->values([$status])
            ->where(['id', '=', $id])
            ->update()
            ->run();
    }

    public function deleteOrder($id)
    {
        $dbPrefix = self::$dbPrefix;

        self::$builder->table("{$dbPrefix}orders")
            ->where(['id', '=', $id])
            ->delete()
            ->run();
    }
}