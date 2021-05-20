<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:59:55
 */

declare(strict_types = 1);
namespace dicr\esputnik\entity;

use dicr\esputnik\Entity;

use function array_merge;

/**
 * Информация о заказах.
 *
 * @link https://esputnik.com/api/ns1_ordersInfo.html
 */
class OrdersInfo extends Entity
{
    /** @var string Дата последнего заказа в формате "YYYY-MM-DD\THH:mm:ss. */
    public $lastDate;

    /** @var int Общее количество заказов. */
    public $count;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['lastDate', 'date', 'format' => 'php:Y-m-d\TH:i:s'],

            ['count', 'integer', 'min' => 0],
            ['count', 'filter', 'filter' => 'intval']
        ]);
    }
}
