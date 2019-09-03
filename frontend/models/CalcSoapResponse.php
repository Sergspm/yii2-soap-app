<?php declare(strict_types=1);

namespace frontend\models;

/**
 * Class CalcSoapResponse
 * @package frontend\models
 */
class CalcSoapResponse
{
    public $price;
    public $info;
    public $error;

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->error
            ? ['error' => $this->error]
            : ['price' => $this->price, 'info' => $this->info]
        ) ?: '{}';
    }
}
