<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:53:50
 */

declare(strict_types = 1);
namespace dicr\esputnik;

use dicr\json\JsonEntity;

/**
 * Class Entity
 */
abstract class Entity extends JsonEntity
{
    /**
     * @inheritDoc
     */
    public function attributeFields(): array
    {
        // отключаем конвертирование названия полей
        return [];
    }
}
