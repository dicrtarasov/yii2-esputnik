<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:58:06
 */

declare(strict_types = 1);
namespace dicr\esputnik\entity;

use dicr\esputnik\Entity;

/**
 * Устройство.
 *
 * @link https://esputnik.com/api/ns0_device.html
 */
class Device extends Entity
{
    /** @var string Для использования пуш уведомлений типа Notification */
    public const CLIENT_VERSION_NATIVE = 'native';

    /** @var string Для использования пуш уведомлений типа Data (по умолчанию) */
    public const CLIENT_VERSION_ESPUTNIK_1 = 'esputnik-1';

    /** @var string[] */
    public const CLIENT_VERSION = [
        self::CLIENT_VERSION_NATIVE, self::CLIENT_VERSION_ESPUTNIK_1
    ];

    /**
     * @var string Идентификатор приложения (UUID), который выдается при регистрации приложения в eSputnik.
     * Выводится пользователю в настройках.
     */
    public $appId;

    /** @var string Модель устройства (произвольное значение - до 50 символов). */
    public $deviceModel;

    /** @var string Операционная система девайса. */
    public $os;

    /** @var string Локаль (например en_UK, ru_UA, ua_UA). */
    public $locale;

    /** @var string Версия SDK используемая в приложении (CLIENT_VERSION_*) */
    public $clientVersion;

    /** @var string Версия мобильного приложения */
    public $appVersion;

    /** @var bool Флаг активности токена */
    public $active;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['deviceModel', 'string', 'max' => 50],

            ['clientVersion', 'in', 'range' => self::CLIENT_VERSION],

            ['active', 'boolean'],
            ['active', 'filter', 'filter' => 'boolval', 'skipOnEmpty' => true]
        ]);
    }
}
