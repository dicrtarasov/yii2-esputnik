<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:56:40
 */

declare(strict_types = 1);
namespace dicr\esputnik\request;

use dicr\esputnik\entity\Contact;
use dicr\esputnik\entity\ContactField;
use dicr\esputnik\ESputnikRequest;
use dicr\json\EntityValidator;
use yii\base\Exception;
use yii\httpclient\Request;

/**
 * Запрос импорта контактов.
 *
 * @link https://esputnik.com/support/metody-api-dlya-dobavleniya-kontaktov#contentAnchor1
 * @link https://esputnik.com/api/el_ns0_contactsBulkUpdate.html
 */
class ContactsRequest extends ESputnikRequest
{
    /** @var string Уникальность по email. */
    public const DEDUPE_ON_EMAIL = 'email';

    /** @var string Уникальность по sms. */
    public const DEDUPE_ON_SMS = 'sms';

    /** @var string Уникальность по токену для push. */
    public const DEDUPE_ON_PUSH = 'push';

    /** @var string Уникальность по токену для push. */
    public const DEDUPE_ON_WEB_PUSH = 'webpush';

    /**
     * @var string Уникальность по sms или email - сначала проверяются совпадения по sms;
     * затем, если таковых не было найдено - проверяются по email.
     */
    public const DEDUPE_ON_EMAIL_OR_SMS = 'email_or_sms';

    /** @var string Уникальность по идентификатору контакта. */
    public const DEDUPE_ON_ID = 'id';

    /**
     * @var string Уникальность по дополнительному полю.
     * Чтобы указать, какое именно поле использовать, нужно установить свойство fieldId в объекте ContactsBulkUpdate.
     * Содержимое поля ограничено 50 символами.
     */
    public const DEDUPE_ON_FIELD = 'fieldId';

    /** @var string[] Свойство для определения уникальности контакта. */
    public const DEDUPE_ON = [
        self::DEDUPE_ON_EMAIL, self::DEDUPE_ON_SMS, self::DEDUPE_ON_PUSH, self::DEDUPE_ON_WEB_PUSH,
        self::DEDUPE_ON_EMAIL_OR_SMS, self::DEDUPE_ON_ID, self::DEDUPE_ON_FIELD
    ];

    /** @var Contact[] Обязательный параметр. Список контактов (не более 3000), которые будут добавлены/обновлены. */
    public $contacts;

    /**
     * @var string Обязательный параметр. Поле для определения уникальности контакта.
     * Обратите внимание: поле, которое указано в параметре dedupeOn, не будет обновлено.
     */
    public $dedupeOn;

    /**
     * @var ?int Дополнительное поле, по которому будет определяться уникальность контакта.
     * Принимается во внимание только в случае, если для свойства dedupeOnProperty установлено значение fieldId.
     */
    public $fieldId;

    /**
     * @var ContactField[]|null Список полей контакта, которые будут обновляться.
     * Если не указать данный параметр, то обновятся все поля контакта.
     * Если данные для обновления не будут переданы, то поля обновятся значением null.
     */
    public $contactFields;

    /**
     * @var int[]|null Список идентификаторов дополнительных полей, которые будут обновляться.
     * Будут обновлены только те дополнительные поля контакта, идентификаторы которых указаны в этом списке.
     * При обновлении контакта это поле становится обязательным.
     */
    public $customFieldsIDs;

    /** @var string[] Список названий групп, в которые будут добавлены новые/обновленные контакты. */
    public $groupNames;

    /** @var string[] Список названий групп, из которых будут исключены новые/обновленные контакты. */
    public $groupNamesExclude = [];

    /** @var bool Восстанавливать удаленные ранее контакты или нет. */
    public $restoreDeleted = false;

    /**
     * @var string Идентификатор-ключ типа события. Событие будет сгенерировано для каждого контакта.
     * Если в системе нет типа события с таким ключом, он будет создан.
     */
    public $eventKeyForNewContacts;

    /**
     * @inheritDoc
     */
    public function attributeEntities(): array
    {
        return array_merge(parent::attributeEntities(), [
            'contacts' => [Contact::class],
            'contactFields' => [ContactField::class]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['contacts', 'contactFields'], EntityValidator::class],

            ['dedupeOn', 'required'],
            ['dedupeOn', 'in', 'range' => self::DEDUPE_ON],

            ['fieldId', 'default'],
            ['fieldId', 'required', 'when' => fn() => $this->dedupeOn === self::DEDUPE_ON_FIELD],
            ['fieldId', 'integer', 'min' => 1],
            ['fieldId', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['customFieldsIDs', 'default'],
            ['customFieldsIDs', 'each', 'rule' => ['required']],
            ['customFieldsIDs', 'each', 'rule' => ['integer', 'min' => 1]],
            ['customFieldsIDs', 'each', 'rule' => ['filter', 'filter' => 'intval']],

            ['groupNames', 'required'],
            ['groupNames', 'each', 'rule' => ['trim']],
            ['groupNames', 'each', 'rule' => ['required']],

            ['groupNamesExclude', 'default'],
            ['groupNamesExclude', 'each', 'rule' => ['trim']],
            ['groupNamesExclude', 'each', 'rule' => ['required']],

            ['restoreDeleted', 'default', 'value' => false],
            ['restoreDeleted', 'boolean'],
            ['restoreDeleted', 'filter', 'filter' => 'boolval'],

            ['eventKeyForNewContacts', 'trim'],
            ['eventKeyForNewContacts', 'required']
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function httpRequest(): Request
    {
        return $this->api->httpClient->post('/v1/contacts', $this->json);
    }

    /**
     * {@inheritDoc}
     * @return ContactsResponse
     */
    public function send(): ContactsResponse
    {
        $res = new ContactsResponse([
            'json' => parent::send()
        ]);

        if (! empty($res->errorMessage)) {
            throw new Exception($res->errorMessage);
        }

        return $res;
    }
}
