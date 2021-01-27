<?php

require_once __DIR__ . '/../../vendor/autoload.php';

try {
    $amo = new \AmoCRM2\Client($account_link, $token);

    // Добавление неразобранных заявок
    // Метод позволяет добавлять неразобранные заявки по одной или пакетно

    $unsorted = $amo->unsorted;
    $unsorted['source'] = 'some@mail.from';
    $unsorted['source_uid'] = '06ea27be-b26e-4ce4-8c20-cb4261a65752'; // Уникальный идентификатор заявки

    // Данные заявки (зависят от категории)
    $unsorted['source_data'] = [
        'from' => [
            'email' => 'info@site.hh.ru',
            'name' => 'HeadHunter',
        ],
        'date' => 1446544372,
        'subject' => 'Any job for me?',
        'thread_id' => 11774, // Уникальный идентификатор цепочки писем
        'message_id' => 23698, // Уникальный идентификатор письма
    ];

    // Сделка которая будет создана после одобрения заявки.
    $lead = $amo->lead;
    $lead['name'] = 'New lead from this email';
    $lead['price'] = 3000;
    $lead['tags'] = ['тест1', 'тест2'];

    // Примечания, которые появятся в сделке после принятия неразобранного
    $note = $amo->note;
    $note['element_type'] = \AmoCRM\Models\Note::TYPE_LEAD; // 1 - contact, 2 - lead
    $note['note_type'] = \AmoCRM\Models\Note::COMMON; // @see https://developers.amocrm.ru/rest_api/notes_type.php
    $note['text'] = 'Примечания, которые появятся в сделке после принятия неразобранного';
    $lead['notes'] = $note;

    // Присоединение сделки к неразобранному
    $unsorted->addDataLead($lead);

    // Добавление неразобранной заявки с типом MAIL
    $unsortedId = $unsorted->apiAddMail();
    print_r($unsortedId);

} catch (\AmoCRM2\Exception $e) {
    printf('Error (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
