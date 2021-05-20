<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:58:15
 */

declare(strict_types = 1);
namespace dicr\esputnik\entity;

use dicr\esputnik\Entity;

use function array_merge;

/**
 * Группа.
 *
 * @link https://esputnik.com/api/ns0_groupDto.html
 */
class GroupDTO extends Entity
{
    /** @var string статическая группа (список) */
    public const TYPE_STATIC = 'Static';

    /** @var string условная группа */
    public const TYPE_DYNAMIC = 'Dynamic';

    /** @var string составная группа. */
    public const TYPE_COMBINED = 'Combined';

    /** @var string[] */
    public const TYPE = [
        self::TYPE_STATIC, self::TYPE_DYNAMIC, self::TYPE_COMBINED
    ];

    /** @var int Идентификатор группы. */
    public $id;

    /** @var string Название группы. */
    public $name;

    /** @var string Тип группы. (TYPE_*) */
    public $type;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['id', 'integer', 'min' => 1],
            ['id', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['name', 'trim'],
            ['name', 'required'],

            ['type', 'required'],
            ['type', 'in', 'range' => self::TYPE]
        ]);
    }
}
