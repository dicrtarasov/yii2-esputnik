<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:53:50
 */

declare(strict_types = 1);
namespace dicr\esputnik\request;

use dicr\esputnik\entity\Channel;
use dicr\esputnik\entity\Contact;
use dicr\esputnik\ESputnikResponse;

use function is_array;

/**
 * Ответ на запрос обновления контактов.
 *
 * @link https://esputnik.com/api/el_ns0_contactBulkUpdateResult.html
 */
class ContactsResponse extends ESputnikResponse
{
    /**
     * @var Contact[]|null Список контактов, которые по каким-либо причинам не удалось добавить/обновить.
     */
    public $failedContacts;

    /**
     * @var ?string Идентификатор асинхронной сессии импорта.
     * Может быть использован для получения статуса импорта с помощью метода /v1/importstatus.
     */
    public $asyncSessionId;

    /** @var ?string Информация о произошедшей ошибке. */
    public $errorMessage;

    /**
     * @inheritDoc
     */
    public function attributesFromJson(): array
    {
        return array_merge(parent::attributesFromJson(), [
            // если вместо массива передан один элемент, то всегда переводим в массив
            'failedContacts' => static function($val) {
                if (is_array($val)) {
                    if (isset($val['channels'])) {
                        $val = new Channel($val);
                    } else {
                        foreach ($val as &$v) {
                            $v = new Channel($v);
                        }

                        unset($v);
                    }
                } else {
                    $val = null;
                }

                return $val;
            }
        ]);
    }

}
