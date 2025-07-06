<?php

namespace App\Services;

class Cart
{
    public function count()
    {
        return session()->get('cart.items', []) ? count(session()->get('cart.items', [])) : 0;
    }

    public function add($product)
    {
        $cart = session()->get('cart.items', []);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }
        
        session()->put('cart.items', $cart);
    }

    public function remove($productId)
    {
        $cart = session()->get('cart.items', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart.items', $cart);
        }
    }

    public function getItems()
    {
        return session()->get('cart.items', []);
    }

    public function getTotal()
    {
        $total = 0;
        $items = $this->getItems();
        
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }

    public function clear()
    {
        session()->forget('cart.items');
    }
}
