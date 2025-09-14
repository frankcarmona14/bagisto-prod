<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Facades\Cart;
use Webkul\MagicAI\Facades\MagicAI;
use Webkul\Sales\Repositories\OrderRepository;

class OnepageController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        Event::dispatch('checkout.load.index');

        /**
         * If guest checkout is not allowed then redirect back to the cart page
         */
        if (
            ! auth()->guard('customer')->check()
            && ! core()->getConfigData('sales.checkout.shopping_cart.allow_guest_checkout')
        ) {
            return redirect()->route('shop.customer.session.index');
        }

        /**
         * If user is suspended then redirect back to the cart page
         */
        if (auth()->guard('customer')->user()?->is_suspended) {
            session()->flash('warning', trans('shop::app.checkout.cart.suspended-account-message'));

            return redirect()->route('shop.checkout.cart.index');
        }

        /**
         * If cart has errors then redirect back to the cart page
         */
        if (Cart::hasError()) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        /**
         * If cart is has downloadable items and customer is not logged in
         * then redirect back to the cart page
         */
        if (
            ! auth()->guard('customer')->check()
            && (
                $cart->hasDownloadableItems()
                || ! $cart->hasGuestCheckoutItems()
            )
        ) {
            return redirect()->route('shop.customer.session.index');
        }

        return view('shop::checkout.onepage.index', compact('cart'));
    }

    /**
     * Order success page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function success(OrderRepository $orderRepository) {
        if (! $order = $orderRepository->find(session('order_id'))) {
            return redirect()->route('shop.checkout.cart.index');
        }

        // Redirección a WhatsApp con mensaje configurable
        $whatsAppPhone   = config('whatsapp.phone');
        $whatsAppMessage = config('whatsapp.message');

        if (! empty($whatsAppPhone)) {
            $customerName = $order->customer_full_name ?? trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? ''));

            $grandFormatted = '$' . number_format((float) $order->grand_total, 2, ',', '.') . ' COP';

            $itemsLines = [];
            foreach ($order->items as $item) {
                $qty    = (int) $item->qty_ordered;
                $iTotal = '$' . number_format((float) $item->total, 2, ',', '.') . ' COP';
                $itemsLines[] = '* ' . $item->name . ' X ' . $qty . ' = ' . $iTotal;
            }
            $itemsList = implode("\n", $itemsLines);

            // Teléfono desde la dirección de facturación/envío o del cliente
            $phone = $order->billing_address?->phone
                ?? $order->shipping_address?->phone
                ?? $order->customer?->phone
                ?? '';

            $replacements = [
                '{order_id}'      => '#' . $order->increment_id,
                '{total}'         => $grandFormatted,
                '{customer_name}' => $customerName,
                '{phone}'         => $phone,
                '{items_list}'    => $itemsList,
            ];

            // Emojis robustos a encoding: se generan desde secuencias JSON (UTF-8 real)
            $emojiClipboard = json_decode("\"\ud83d\udccb\"");         // 📋 U+1F4CB
            $emojiBag       = json_decode("\"\ud83d\udecd\ufe0f\"");   // 🛍️ U+1F6CD U+FE0F
            $emojiBox       = json_decode("\"\ud83d\udce6\"");         // 📦 U+1F4E6
            $emojiPray      = json_decode("\"\ud83d\ude4f\"");         // 🙏 U+1F64F
            $emojiCelebrate = json_decode("\"\ud83c\udf89\"");         // 🎉 U+1F389
            $emojiUser      = json_decode("\"\ud83d\udc64\"");         // 👤 U+1F464

            // Si hay plantilla en config/.env, usarla con placeholders
            if (is_string($whatsAppMessage) && $whatsAppMessage !== '') {
                $text = strtr($whatsAppMessage, array_merge($replacements, [
                    '{emoji_clipboard}' => $emojiClipboard,
                    '{emoji_bag}'       => $emojiBag,
                    '{emoji_box}'       => $emojiBox,
                    '{emoji_pray}'      => $emojiPray,
                    '{emoji_celebrate}' => $emojiCelebrate,
                    '{emoji_user}'      => $emojiUser,
                ]));
            } else {
                $text = "Tu orden ha sido confirmada exitosamente! " . $emojiCelebrate . "\n\n"
                    . $emojiUser . " Datos del cliente:\n"
                    . "Nombre: {$replacements['{customer_name}']}\n"
                    . "Teléfono: {$replacements['{phone}']}\n\n"
                    . $emojiClipboard . " Detalles del pedido:\n"
                    . "Número de orden: {$replacements['{order_id}']}\n"
                    . "Total: {$replacements['{total}']}\n\n"
                    . $emojiBag . " Productos:\n"
                    . $itemsList . "\n\n"
                    . $emojiBox . " Tu pedido será procesado y enviado pronto. Te mantendremos informado sobre el estado de tu envío.\n\n"
                    . "¡Gracias por confiar en nosotros! " . $emojiPray;
            }

            // Probar API clásica que suele preservar mejor emojis en algunos dispositivos
            $query = http_build_query(
                [
                    'phone' => preg_replace('/\D+/', '', $whatsAppPhone),
                    'text'  => $text,
                ],
                '',
                '&',
                PHP_QUERY_RFC3986
            );
            $url   = 'https://api.whatsapp.com/send?' . $query;

            return redirect()->away($url);
        }

        if (
            core()->getConfigData('general.magic_ai.settings.enabled')
            && core()->getConfigData('general.magic_ai.checkout_message.enabled')
            && ! empty(core()->getConfigData('general.magic_ai.checkout_message.prompt'))
        ) {

            try {
                $model = core()->getConfigData('general.magic_ai.checkout_message.model');

                $response = MagicAI::setModel($model)
                    ->setTemperature(0)
                    ->setPrompt($this->getCheckoutPrompt($order))
                    ->ask();

                $order->checkout_message = $response;
            } catch (\Exception $e) {
            }
        }

        return view('shop::checkout.success', compact('order'));
    }

    /**
     * Order success page.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return string
     */
    public function getCheckoutPrompt($order) {
        $prompt = core()->getConfigData('general.magic_ai.checkout_message.prompt');

        $products = '';

        foreach ($order->items as $item) {
            $products .= "Name: $item->name\n";
            $products .= "Qty: $item->qty_ordered\n";
            $products .= 'Price: ' . core()->formatPrice($item->total) . "\n\n";
        }

        $prompt .= "\n\nProduct Details:\n $products";

        $prompt .= "Customer Details:\n $order->customer_full_name \n\n";

        $prompt .= "Current Locale:\n " . core()->getCurrentLocale()->name . "\n\n";

        $prompt .= "Store Name:\n" . core()->getCurrentChannel()->name;

        return $prompt;
    }
}
