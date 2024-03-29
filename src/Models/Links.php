<?php

namespace AmoCRM2\Models;

/**
 * Class Links
 *
 * Класс модель для работы со Связями между сущностями
 *
 * @package AmoCRM2\Models
 * @author mb@baso-it.ru
 * @author dotzero <mail@dotzero.ru>
 * @link https://github.com/dotzero/amocrm-php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Links extends AbstractModel
{
    /**
     * @var array Список доступный полей для модели (исключая кастомные поля)
     */
    protected $fields = [
        'from',
        'from_id',
        'to',
        'to_id',
        'from_catalog_id',
        'to_catalog_id',
        'quantity',
        'main_contact',
        'price_id',
        'catalog_id',
        'updated_by',
        'metadata'
    ];

    /**
     * Связи между сущностями
     *
     * Метод для получения связей между сущностями аккаунта
     *
     * @link https://developers.amocrm.ru/rest_api/links/list.php
     * @param array $parameters Массив параметров к amoCRM API
     * @return array Ответ amoCRM API
     */
    public function apiList($parameters)
    {
        if (!isset($parameters['links'])) {
            $parameters = [
                'links' => [
                    $parameters
                ]
            ];
        }

        $response = $this->getRequest('/private/api/v2/json/links/list', $parameters);

        return isset($response['links']) ? $response['links'] : [];
    }

    /**
     * Список связанных сущностей, метод в4
     *
     * Метод для получения связей между сущностями аккаунта
     *
     * @link https://www.amocrm.ru/developers/content/crm_platform/entity-links-api#links-list
     * @param array $parameters Массив параметров к amoCRM API
     * @return array Ответ amoCRM API
     */
    public function apiv4List($entity_type, $entity_id, $parameters = [])
    {
        $response = $this->getRequest('/api/v4/'.$entity_type.'/'.$entity_id.'/links', $parameters);

        return isset($response['_embedded']['links']) ? $response['_embedded']['links'] : [];
    }

    /**
     * Массовый список связанных сущностей, метод в4
     *
     * Метод позволяет получить связанные сущности по ID-ам основных сущностей
     *
     * @link https://www.amocrm.ru/developers/content/crm_platform/entity-links-api#mass-links-list
     * @param array $parameters Массив параметров к amoCRM API
     * @return array Ответ amoCRM API
     */
    public function apiv4Mass($entity_type, $parameters = [])
    {
        $response = $this->getRequest('/api/v4/'.$entity_type.'/links', $parameters);

        return isset($response['_embedded']['links']) ? $response['_embedded']['links'] : [];
    }

    /**
     * Установка связи между сущностями
     *
     * Метод позволяет устанавливать связи между сущностями
     *
     * @link https://developers.amocrm.ru/rest_api/links/set.php
     * @param array $links Массив связей для пакетного добавления
     * @return bool Флаг успешности выполнения запроса
     */
    public function apiLink($links = [])
    {
        if (empty($links)) {
            $links = [$this];
        }

        $parameters = [
            'links' => [
                'link' => [],
            ],
        ];

        foreach ($links AS $link) {
            $parameters['links']['link'][] = $link->getValues();
        }

        $response = $this->postRequest('/private/api/v2/json/links/set', $parameters);

        if (!isset($response['links']['link']['errors'])) {
            return false;
        }

        return empty($response['links']['link']['errors']);
    }

    /**
     * Привязка сущностей, метод в4
     *
     * Метод позволяет прикреплять сущности к основной сущности
     *
     * @link https://www.amocrm.ru/developers/content/crm_platform/entity-links-api#links-link
     * @return array Ответ amoCRM API
     */
    public function apiv4Link()
    {
        $parameters = [];

        $values = $this->getValues(); 

        $from_id = $values['from_id'];
        $from = $values['from'];

        $to_id = $values['to_id'];
        $to = $values['to'];

        $main_contact = $values['main_contact'] ?? NULL;
        $quantity = $values['quantity'] ?? NULL;
        $catalog_id = $values['catalog_id'] ?? NULL;
        $price_id = $values['price_id'] ?? NULL;

        $metadata = [];
        if (!empty($main_contact))
        {
            $metadata['main_contact'] = $main_contact;
        }
        if (!empty($quantity))
        {
            $metadata['quantity'] = $quantity;
        }
        if (!empty($catalog_id))
        {
            $metadata['catalog_id'] = $catalog_id;
        }
        if (!empty($price_id))
        {
            $metadata['price_id'] = $price_id;
        }

        $parameters[] = ['to_entity_id' => $to_id, 'to_entity_type' => $to, 'metadata' => $metadata];
       
        $response = $this->postv4Request('/api/v4/'.$from.'/'.$from_id.'/link', $parameters);

        return isset($response['_embedded']['links']) ? $response['_embedded']['links'] : [];
    }

    /**
     * Разрыв связи между сущностями
     *
     * Метод позволяет удалять связи между сущностями
     *
     * @link https://developers.amocrm.ru/rest_api/links/set.php
     * @param array $links Массив связей для пакетного удаления
     * @return bool Флаг успешности выполнения запроса
     */
    public function apiUnlink($links = [])
    {
        if (empty($links)) {
            $links = [$this];
        }

        $parameters = [
            'links' => [
                'unlink' => [],
            ],
        ];

        foreach ($links AS $link) {
            $parameters['links']['unlink'][] = $link->getValues();
        }

        $response = $this->postRequest('/private/api/v2/json/links/set', $parameters);

        if (!isset($response['links']['unlink']['errors'])) {
            return false;
        }

        return empty($response['links']['unlink']['errors']);
    }
    /**
     * Отвязка сущностей, метод в4
     *
     * Метод позволяет открепить сущности у основной сущности
     *
     * @link https://www.amocrm.ru/developers/content/crm_platform/entity-links-api#links-unlink
     * @return array Ответ amoCRM API
     */
    public function apiv4Unlink()
    {
        $parameters = [];

        $values = $this->getValues(); 

        $from_id = $values['from_id'];
        $from = $values['from'];

        $to_id = $values['to_id'];
        $to = $values['to'];

        $updated_by = $values['updated_by'] ?? NULL;
        $catalog_id = $values['catalog_id'] ?? NULL;

        $metadata = [];
        if (!empty($updated_by))
        {
            $metadata['updated_by'] = $updated_by;
        }
        if (!empty($catalog_id))
        {
            $metadata['catalog_id'] = $catalog_id;
        }

        $parameters[] = ['to_entity_id' => $to_id, 'to_entity_type' => $to, 'metadata' => $metadata];
       

        $response = $this->postv4Request('/api/v4/'.$from.'/'.$from_id.'/unlink', $parameters);

        return isset($response['_embedded']['links']) ? $response['_embedded']['links'] : [];
    }
}
