<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:58:36
 */

declare(strict_types = 1);
namespace dicr\esputnik\entity;

use dicr\esputnik\Entity;

/**
 * Подписка Web-push.
 *
 * @link https://esputnik.com/api/ns0_webPushSubscription.html
 */
class WebPushSubscription extends Entity
{
    /** @var int Id приложения подписки (int) в eSputnik */
    public $appId;

    /** @var string Идентификатор браузера */
    public $userAgent;

    /** @var string Версия браузера */
    public $userAgentVersion;

    /** @var string Язык */
    public $userAgentLanguage;

    /** @var string Операционная система */
    public $os;

    /** @var string IP пользователя */
    public $ip;

    /** @var string Страница подписки */
    public $subscriptionPage;

    /** @var string */
    public $swActiveVersion;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['appId', 'integer', 'min' => 1],
            ['appId', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true]
        ]);
    }
}
