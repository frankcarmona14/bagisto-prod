<?php

return [
    // Número en formato internacional sin símbolos, por ejemplo: 573236908719
    'phone'   => env('WHATSAPP_PHONE', ''),

    // Plantilla del mensaje con placeholders. Puedes sobreescribirla en .env con WHATSAPP_MESSAGE
    // Placeholders disponibles:
    // {store_name}, {order_id}, {total}, {customer_name}, {items_list}
    // {emoji_clipboard}, {emoji_bag}, {emoji_box}, {emoji_pray}, {emoji_user}
    'message' => env(
        'WHATSAPP_MESSAGE',
        "Tu orden ha sido confirmada exitosamente! {emoji_celebrate}\n\n"
            . "{emoji_user} Datos del cliente:\n"
            . "Nombre: {customer_name}\n"
            . "Teléfono: {phone}\n\n"
            . "{emoji_clipboard} Detalles del pedido:\n"
            . "Número de orden: {order_id}\n"
            . "Total: {total}\n\n"
            . "{emoji_bag} Productos:\n"
            . "{items_list}\n\n"
            . "{emoji_box} Tu pedido será procesado y enviado pronto. Te mantendremos informado sobre el estado de tu envío.\n\n"
            . "¡Gracias por confiar en nosotros! {emoji_pray}"
    ),
];
