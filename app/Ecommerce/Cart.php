<?php

namespace App\Ecommerce;

use App\Models\User;
use App\Ecommerce\Money;
use Illuminate\Support\Str;

class Cart
{
    protected $user;
    protected $changed = false;
    protected $stockChanged = [];

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function add($products)
    {
        $this->user->cart()->syncWithoutDetaching(
            $this->getStorePayload($products)
        );
    }

    public function update($productId, $quantity)
    {
        $this->user->cart()->updateExistingPivot($productId, [
            'quantity' => $quantity
        ]);
    }

    public function delete($productId)
    {
        $this->user->cart()->detach($productId);
    }

    public function empty()
    {
        $this->user->cart()->detach();
    }

    public function isEmpty()
    {
        return $this->user->cart->sum('pivot.quantity') === 0;
    }

    public function subTotal()
    {
        $subTotal = $this->user->cart->sum(function ($product) {
            return $product->price->amount() * $product->pivot->quantity;
        });

        return new Money($subTotal);
    }

    public function total()
    {
        return $this->subTotal();
    }

    public function sync()
    {
        $this->user->cart->each(function ($product) {
            $quantity = $product->minStock($product->pivot->quantity);
            $this->stockChanged[Str::lower($product->id)] = $quantity != $product->pivot->quantity;
            // $this->changed = $quantity != $product->pivot->quantity;

            $product->pivot->update([
                'quantity' => $quantity
            ]);
        });
    }

    public function hasChanged()
    {
        foreach ($this->stockChanged as $key => $value) {
            if ($value) {
                $this->changed = true;
                break;
            }
        }
        return $this->changed;
    }

    protected function getStorePayload($products)
    {
        return collect($products)->keyBy('id')->map(function ($product) {
            return [
                'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id'])
            ];
        })->toArray();
    }

    protected function getCurrentQuantity($prodictId)
    {
        if ($product = $this->user->cart->where('id', $prodictId)->first()) {
            return $product->pivot->quantity;
        }
        return 0;
    }
}
