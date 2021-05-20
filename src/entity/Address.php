<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:57:11
 */

declare(strict_types = 1);
namespace dicr\esputnik\entity;

use dicr\esputnik\Entity;

/**
 * Полный адрес контакта.
 *
 * @link https://esputnik.com/api/ns0_address.html
 */
class Address extends Entity
{
    /** @var string Область */
    public $region;

    /** @var string Город */
    public $town;

    /** @var string Адрес */
    public $address;

    /** @var string Почтовый индекс. */
    public $postcode;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['postcode', 'number', 'min' => 1, 'max' => 999999]
        ]);
    }
}
