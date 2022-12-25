<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

    // Количество выводимых карточек новых объявлений
    'newOffersCount' => 8,

    // Количество выводимых карточек самых обсуждаемых объявлений
    'mostTalkedOffersCount' => 8,

    // Максимальное количество в анонсе карточки объявления
    // Согласно ТЗ "Анонс, не более 55 символов." 52 - с учётом многоточия в конце оборванной строки
    'offerTextLength' => 52,
];
