<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SessionCartHelper;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Pre-fill name from guest session if available
        $guestName = SessionCartHelper::getGuestName();
        return view('auth.register', ['guestName' => $guestName]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Use guest name from session if available and user didn't provide a different name
        $name = SessionCartHelper::getGuestName() ?? $request->name;

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('customer');

        event(new Registered($user));

        Auth::login($user);

        // Migrate guest cart to user cart if exists
        $guestCart = SessionCartHelper::getCart();
        if (!empty($guestCart)) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            
            foreach ($guestCart as $item) {
                $existingItem = $cart->cartItems()
                    ->where('product_id', $item['product_id'])
                    ->where('size', $item['size'])
                    ->where('color', $item['color'])
                    ->first();

                if ($existingItem) {
                    $existingItem->update([
                        'quantity' => $existingItem->quantity + $item['quantity'],
                        'price' => $item['price'],
                    ]);
                } else {
                    $cart->cartItems()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'size' => $item['size'],
                        'color' => $item['color'],
                    ]);
                }
            }

            // Clear guest cart
            SessionCartHelper::clear();
        }

        // Clear guest name
        SessionCartHelper::clearGuestName();

        return redirect(route('dashboard', absolute: false));
    }
}
