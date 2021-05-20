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

/**
 * Канал контакта.
 *
 * @link https://esputnik.com/api/ns0_channel.html
 */
class Channel extends Entity
{
    /** @var string */
    public const TYPE_EMAIL = 'email';

    /** @var string */
    public const TYPE_SMS = 'sms';

    /** @var string */
    public const TYPE_VIBER = 'viber';

    /** @var string */
    public const TYPE_MOBILE_PUSH = 'mobilepush';

    /** @var string */
    public const TYPE_WEB_PUSH = 'webpush';

    /** @var string[] типы */
    public const TYPE = [
        self::TYPE_EMAIL, self::TYPE_SMS, self::TYPE_VIBER, self::TYPE_MOBILE_PUSH, self::TYPE_WEB_PUSH
    ];

    /** @var string Тип медиа-канала. (TYPE_*) */
    public $type;

    /** @var string Email-адрес, номер телефона либо пуш-токен. */
    public $value;

    /** @var ?Device */
    public $device;

    /** @var ?WebPushSubscription */
    public $webPushSubscription;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['type', 'required'],
            ['type', 'in', 'range' => self::TYPE],

            ['value', 'trim'],
            ['value', 'required'],

            [['device', 'webPushSubscription'], 'default'],
            [['device', 'webPushSubscription'], EntityValidator::class],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function attributeEntities(): array
    {
        return array_merge(parent::attributeEntities(), [
            'device' => Device::class,
            'webPushSubscription' => WebPushSubscription::class
        ]);
    }
}
