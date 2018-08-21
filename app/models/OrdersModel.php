<?php

namespace app\models;

class OrdersModel extends Model
{
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
}