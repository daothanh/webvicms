<?php


namespace Modules\Commerce\Support;

use Illuminate\Contracts\Support\Arrayable;

class Cart implements Arrayable
{
    /**
     * @var \Illuminate\Support\Collection|CartItem[]
     */
    protected $items;

    public function __construct($items = [])
    {
        $this->items = collect();
        foreach ($items as $item) {
            if ($item instanceof CartItem) {
                $this->add($item);
            } elseif (is_array($item)) {
                $this->addFromArray($item);
            }
        }
    }

    public function addFromArray(array $item) {
        if (isset($item['name']) && $item['price']) {
            $cartItem = new CartItem($item['name'], $item['price'], $item['qty'] ?? 1, $item['vatPercent'] ?? 0);
            $this->add($cartItem);
        }
    }
    /**
     * Thêm 1 item vào giỏ hàng
     *
     * @param CartItem $item
     */
    public function add(CartItem $item) {
        if (!$item->getKey()) {
            $key = 'item_'.($this->count() + 1);
            $item->setKey($key);
        }
        $this->items->add($item);
    }

    /**
     * Remove 1 item khỏi giỏ hàng
     *
     * @param CartItem $item
     */
    public function remove(CartItem $item) {
        $this->items = $this->items->filter(function ($cartItem) use ($item) {
            return $cartItem->getKey() !== $item->getKey();
        });
    }

    /**
     * Cập nhật 1 item trong giỏ hàng
     *
     * @param $itemKey
     * @param array $data
     * @return bool
     */
    public function update($itemKey, Array $data) {
        $ok = false;
        foreach ($this->items as $key => $cartItem) {
            if ($itemKey === $cartItem->getKey()) {
                $item = $this->items[$key];
                foreach ($data as $prop => $value) {
                    $item->{$prop} = $value;
                }
                $this->items[$key] = $item;
                $ok = true;
                break;
            }
        }
        return $ok;
    }

    public function count() {
        return $this->items->count();
    }

    public function getItems() {
        return $this->items;
    }

    public function subtotal() {
        $subtotal = 0;
        foreach($this->items as $item) {
            $subtotal += $item->subtotal();
        }
        return $subtotal;
    }

    public function total() {
        $total = 0;
        foreach($this->items as $item) {
            $total += $item->total();
        }
        return $total;
    }

    public function vat() {
        $vat = 0;
        foreach($this->items as $item) {
            $vat += $item->vat();
        }
        return $vat;
    }

    public function clear() {
        $this->items->forget($this->items->keys());
    }
    public function toArray() {
        return $this->items->toArray();
    }

    public function toJson($options = 0)
    {
        return $this->items->toJson($options);
    }
 }
