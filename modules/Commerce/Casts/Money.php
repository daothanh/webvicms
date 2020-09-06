<?php


namespace Modules\Commerce\Casts;


use Money\Currency;

class Money implements \Illuminate\Contracts\Database\Eloquent\CastsAttributes
{

    protected $amount;
    protected $currency;

    /**
     * Money constructor.
     * @param $amount
     * @param $currency
     */
    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return new \Money\Money(
            $attributes[$this->amount],
            new Currency($attributes[$this->currency])
        );

    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return [
            $this->amount => (int) $value->getAmount(),
            $this->currency => (string) $value->getCurrency()
        ];
    }
}
