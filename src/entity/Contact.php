<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:57:47
 */

declare(strict_types = 1);

namespace dicr\esputnik\entity;

use dicr\esputnik\Entity;
use dicr\json\EntityValidator;

use function array_merge;

/**
 * Контакт.
 *
 * @link https://esputnik.com/api/ns0_contact.html
 */
class Contact extends Entity
{
    /** @var ?string Имя контакта. Цифры использовать нельзя. */
    public $firstName;

    /** @var ?string Фамилия контакта. Цифры использовать нельзя. */
    public $lastName;

    /** @var Channel[] Обязательный параметр. Список медиа-каналов контакта. */
    public $channels;

    /** @var ?Address Адрес контакта. */
    public $address;

    /** @var ContactField[]|null Значения дополнительных полей контакта. */
    public $fields;

    /**
     * @var ?int Идентификатор каталога.
     * Информацию о каталогах можно получить с помощью метода /v1/addressbooks GET.
     */
    public $addressBookId;

    /** @var ?int Идентификатор контакта. */
    public $id;

    /** @var ?string Ключ контакта. */
    public $contactKey;

    /** @var ?OrdersInfo Информация о заказах. */
    public $ordersInfo;

    /**
     * @var GroupDTO[]|null Список СТАТИЧЕСКИХ групп, в которые входит контакт.
     * Используется только при получении контактов.
     */
    public $groups;

    /** @var ?string Язык контакта. */
    public $languageCode;

    /** @var ?string Временная зона контакта. */
    public $timeZone;

    /**
     * @inheritDoc
     */
    public function attributeEntities(): array
    {
        return array_merge(parent::attributeEntities(), [
            'channels' => [Channel::class],
            'address' => Address::class,
            'fields' => [ContactField::class],
            'ordersInfo' => OrdersInfo::class,
            'groups' => [GroupDTO::class]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['firstName', 'lastName'], 'trim'],
            [['firstName', 'lastName'], 'default'],

            [['channels', 'address', 'fields', 'ordersInfo', 'groups'], EntityValidator::class],

            [['addressBookId', 'id'], 'integer', 'min' => 1],
            [['addressBookId', 'id'], 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
        ]);
    }
}
