<?php

require_once __DIR__ . '/../vendor/autoload.php';

try 
{
    $amo = new \AmoCRM2\Client($account_link, $token);

    // Получение информации по аккаунту в котором произведена авторизация:
    // название, оплаченный период, пользователи аккаунта и их права,
    // справочники дополнительных полей контактов и сделок, справочник статусов сделок,
    // справочник типов событий, справочник типов задач и другие параметры аккаунта.

    // Полный формат
    print_r($amo->account->apiCurrent());

    // Краткий формат (если полный не влезает в буфер консоли)
    print_r($amo->account->apiCurrent(true));

    // Возвращает сведения об авторизвавшемся пользователе.
    print_r($amo->account->apiUser());

    //
    // Примеры V4 
    //
    
    //Получение всех пользователей
    //параметры не обязательные к заполнению
    $users = $amo->account->apiv4Users([
        'with' => ['role', 'group', 'uuid', 'amojo_id'], // доп параметры
        'limit' => 250, // кол-во шт на странице
        'page' => 1, // номер страницы]);
    ]);

    // Получение 1 пользователя по id
    $user = $amo->account->apiv4User([000000, ['with' => []]]);  

} 
catch (\AmoCRM2\Exception $e) 
{
    printf('Error (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
