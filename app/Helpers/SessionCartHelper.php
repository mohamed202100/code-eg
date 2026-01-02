<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SessionCartHelper
{
    const SESSION_KEY = 'guest_cart';
    const COOKIE_KEY = 'guest_cart';
    const GUEST_NAME_KEY = 'guest_name';
    const COOKIE_LIFETIME = 43200; // 30 days in minutes

    /**
     * Get the guest cart from session or cookie
     */
    public static function getCart(): array
    {
        // First try session
        $cart = Session::get(self::SESSION_KEY);
        
        // If session is empty, try to load from cookie
        if (empty($cart)) {
            $request = request();
            $cookieCart = $request->cookie(self::COOKIE_KEY);
            if ($cookieCart) {
                $cart = json_decode($cookieCart, true) ?? [];
                // Sync cookie to session
                Session::put(self::SESSION_KEY, $cart);
            } else {
                $cart = [];
            }
        }
        
        return $cart;
    }

    /**
     * Save cart to both session and cookie
     */
    private static function saveCart(array $cart): void
    {
        Session::put(self::SESSION_KEY, $cart);
        Cookie::queue(Cookie::make(self::COOKIE_KEY, json_encode($cart), self::COOKIE_LIFETIME));
    }

    /**
     * Add item to guest cart
     */
    public static function addItem(int $productId, int $quantity, float $price, ?string $size = null, ?string $color = null): void
    {
        $cart = self::getCart();
        $key = self::generateItemKey($productId, $size, $color);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'size' => $size,
                'color' => $color,
            ];
        }

        self::saveCart($cart);
    }

    /**
     * Update item quantity in guest cart
     */
    public static function updateItemQuantity(string $key, int $quantity): bool
    {
        $cart = self::getCart();
        
        if (!isset($cart[$key])) {
            return false;
        }

        $cart[$key]['quantity'] = max(1, $quantity);
        self::saveCart($cart);
        
        return true;
    }

    /**
     * Increment item quantity
     */
    public static function incrementItem(string $key): bool
    {
        $cart = self::getCart();
        
        if (!isset($cart[$key])) {
            return false;
        }

        $cart[$key]['quantity']++;
        self::saveCart($cart);
        
        return true;
    }

    /**
     * Decrement item quantity
     */
    public static function decrementItem(string $key): bool
    {
        $cart = self::getCart();
        
        if (!isset($cart[$key]) || $cart[$key]['quantity'] <= 1) {
            return false;
        }

        $cart[$key]['quantity']--;
        self::saveCart($cart);
        
        return true;
    }

    /**
     * Remove item from guest cart
     */
    public static function removeItem(string $key): bool
    {
        $cart = self::getCart();
        
        if (!isset($cart[$key])) {
            return false;
        }

        unset($cart[$key]);
        self::saveCart($cart);
        
        return true;
    }

    /**
     * Clear the guest cart
     */
    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
        Cookie::queue(Cookie::forget(self::COOKIE_KEY));
    }

    /**
     * Get cart items with product details
     */
    public static function getCartItemsWithProducts(): array
    {
        $cart = self::getCart();
        $items = [];

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $items[] = [
                    'key' => $key,
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'size' => $item['size'],
                    'color' => $item['color'],
                ];
            }
        }

        return $items;
    }

    /**
     * Calculate total price
     */
    public static function getTotal(): float
    {
        $cart = self::getCart();
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    /**
     * Get cart count
     */
    public static function getCount(): int
    {
        $cart = self::getCart();
        $count = 0;

        foreach ($cart as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }

    /**
     * Generate unique key for cart item
     */
    public static function generateItemKey(int $productId, ?string $size = null, ?string $color = null): string
    {
        return "{$productId}_" . ($size ?? 'no_size') . "_" . ($color ?? 'no_color');
    }

    /**
     * Store guest name in session and cookie
     */
    public static function setGuestName(string $name): void
    {
        Session::put(self::GUEST_NAME_KEY, $name);
        Cookie::queue(Cookie::make('guest_name', $name, self::COOKIE_LIFETIME));
    }

    /**
     * Get guest name from session or cookie
     */
    public static function getGuestName(): ?string
    {
        $name = Session::get(self::GUEST_NAME_KEY);
        if (!$name) {
            $request = request();
            $name = $request->cookie('guest_name');
            if ($name) {
                Session::put(self::GUEST_NAME_KEY, $name);
            }
        }
        return $name;
    }

    /**
     * Clear guest name from session and cookie
     */
    public static function clearGuestName(): void
    {
        Session::forget(self::GUEST_NAME_KEY);
        Cookie::queue(Cookie::forget('guest_name'));
    }
}

