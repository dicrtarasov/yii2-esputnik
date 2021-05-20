<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:57:56
 */

declare(strict_types = 1);
namespace dicr\esputnik\entity;

use dicr\esputnik\Entity;

use function array_merge;

/**
 * Дополнительное поле контакта.
 *
 * @link https://esputnik.com/api/ns0_contactField.html
 */
class ContactField extends Entity
{
    /**
     * @var int Идентификатор дополнительного поля. Может быть получен с помощью метода /v1/addressbooks GET.
     */
    public $id;

    /** @var string */
    public $value;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['id', 'required'],
            ['id', 'integer', 'min' => 1],
            ['id', 'filter', 'filter' => 'intval'],

            ['value', 'trim'],
            ['value', 'required']
        ]);
    }
}
