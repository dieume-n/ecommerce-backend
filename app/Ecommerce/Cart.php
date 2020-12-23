<?php

namespace App\Ecommerce;

use App\Models\User;

class Cart
{
    protected $user;

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
