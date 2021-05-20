<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:53:50
 */

declare(strict_types = 1);
namespace dicr\esputnik;

use dicr\esputnik\request\ContactsRequest;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;

use const CURLOPT_ENCODING;
use const CURLOPT_USERPWD;

/**
 * Class ESputnikAPI
 *
 * @property-read Client $httpClient
 * @link https://esputnik.com/api/index.html
 */
class ESputnikAPI extends Component
{
    /** @var string */
    private const API_URL = 'https://esputnik.com/api';

    /** @var string */
    public $url = self::API_URL;

    /** @var ?string логин пользователя (при использовании ключа API значение не важно) */
    public $user;

    /** @var string пароль пользователя (вместо пароля можно использовать ключ API) */
    public $keyPass;

    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        if (empty($this->url)) {
            throw new InvalidConfigException('url');
        }

        if (empty($this->keyPass)) {
            throw new InvalidConfigException('keyPass');
        }
    }

    /** @var Client */
    private $_httpClient;

    /**
     * HTTP-клиент.
     *
     * @return Client
     */
    public function getHttpClient(): Client
    {
        if ($this->_httpClient === null) {
            $this->_httpClient = new Client([
                'transport' => CurlTransport::class,
                'baseUrl' => $this->url,
                'requestConfig' => [
                    'format' => Client::FORMAT_JSON,
                    'headers' => [
                        'Accept' => 'application/json'
                    ],
                    'options' => [
                        CURLOPT_USERPWD => $this->user . ':' . $this->keyPass,
                        CURLOPT_ENCODING => ''
                    ]
                ],
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ]
            ]);
        }

        return $this->_httpClient;
    }

    /**
     * Создать запрос.
     *
     * @param array $config
     * @return ESputnikRequest
     * @throws InvalidConfigException
     */
    public function request(array $config): ESputnikRequest
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Yii::createObject($config, [$this]);
    }

    /**
     * Запрос contacts.
     *
     * @param array $config
     * @return ContactsRequest
     * @throws InvalidConfigException
     */
    public function contactsRequest(array $config = []): ContactsRequest
    {
        return $this->request(array_merge($config, [
            'class' => ContactsRequest::class
        ]));
    }
}
