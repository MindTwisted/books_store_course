<?php

namespace app\controllers;

use libs\View;
use libs\Auth;
use libs\Validator;
use libs\Input;

use app\models\PaymentTypesModel;

class PaymentTypesController
{
    protected $paymentTypesModel;

    public function __construct()
    {
        $this->paymentTypesModel = new PaymentTypesModel();
    }

    public function index()
    {
        $paymentTypes = $this->paymentTypesModel->getPaymentTypes();

        return View::render([
            'data' => $paymentTypes
        ]);
    }  

}