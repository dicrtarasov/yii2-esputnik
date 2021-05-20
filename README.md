# eSputnik API для Yii2

API: https://esputnik.com/api/

## Конфигурация

```php
$config = [
    'components' => [
        'esputnik' => [
            'class' => dicr\esputnik\ESputnikAPI::class,
            'user' => 'ваш_логин',
            'keyPass' => 'ваш_ключ_или_пароль'
        ]
    ]   
];

```

## Использование

```php

/** @var dicr\esputnik\ESputnikAPI $api */
$api = Yii::$app->get('esputnik');

/** @var dicr\esputnik\request\ContactsRequest $req */
$req = $api->contactsRequest([
    'contacts' => [
        [
            'firstName' => 'Иван',
            'channels' => [
                ['type' => dicr\esputnik\entity\Channel::TYPE_EMAIL, 'value' => 'ivan@mail.com']
            ]
        ],
        [
            'firstName' => 'Сергей',
            'channels' => [
                ['type' => dicr\esputnik\entity\Channel::TYPE_EMAIL, 'value' => 'sergey@mail.com']
            ]
        ]
    ],
    'dedupeOn' => dicr\esputnik\request\ContactsRequest::DEDUPE_ON_EMAIL,
    'groupNames' => ['Покупатели'],
    'eventKeyForNewContacts' => 'NewUsers'
]);

/** @var dicr\esputnik\request\ContactsResponse $res */
$res = $req->send();

if (! empty($res->errorMessage)) {
    throw new yii\base\Exception($res->errorMessage);
}

echo 'Передано: ' . count($req->contacts) . " контактов\n";
 
if (! empty($res->failedContacts)) {
    echo 'Пропущено: ' . count($res->failedContacts) . " контактов\n";
}
```

