<?php


namespace Modules\Commerce\Support;


use Illuminate\Contracts\Support\Arrayable;

class CartItem implements Arrayable
{
    protected $name;
    protected $price;
    protected $qty;
    protected $vatPercent;

    private $key;
    private $options = [];

    public function __construct($name, $price, $qty = 1, $vatPercent = 0)
    {
        $this->name = $name;
        $this->price = $price;
        $this->qty = $qty;
        $this->vatPercent = $vatPercent;
    }

    public function getKey() {
        return $this->key;
    }

    public function setKey($key) {
        $this->key = $key;
    }

    public function total() {
        return $this->subtotal() + $this->vat();
    }

    public function subtotal() {
        return $this->qty * $this->price;
    }

    public function vat() {
        return ($this->subtotal() * $this->vatPercent)/100;
    }

    public function toArray() {
        $properties = [
            'key' => $this->key,
            'name' => $this->name,
            'price' => $this->price,
            'qty' => $this->qty,
            'vat_percent' => $this->vatPercent
        ];
        return array_merge($properties, $this->options);
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        } else {
            $this->options[$name] = $value;
        }
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        } elseif (in_array($name, array_keys($this->options))) {
            return $this->options[$name];
        }
        return null;
    }
}
