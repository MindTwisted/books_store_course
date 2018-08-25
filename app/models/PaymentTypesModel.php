<?php

namespace app\models;

class PaymentTypesModel extends Model
{
    public function getPaymentTypes()
    {
        $dbPrefix = self::$dbPrefix;

        $paymentTypes = self::$builder->table("{$dbPrefix}payment_types")
            ->fields(['id', 'name'])
            ->select()
            ->run();

        return $paymentTypes;
    }

}