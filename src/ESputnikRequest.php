<?php
/*
 * @copyright 2019-2021 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license BSD-3-Clause
 * @version 21.05.21 00:55:38
 */

declare(strict_types = 1);

namespace dicr\esputnik;

use dicr\helper\Log;
use dicr\validate\ValidateException;
use yii\base\Exception;
use yii\httpclient\Client;
use yii\httpclient\Request;

/**
 * Запрос ESputnik.
 *
 */
abstract class ESputnikRequest extends Entity
{
    /** @var ESputnikAPI */
    protected $api;

    /**
     * ESputnikRequest constructor.
     *
     * @param ESputnikAPI $api
     * @param array $config
     */
    public function __construct(ESputnikAPI $api, array $config = [])
    {
        $this->api = $api;

        parent::__construct($config);
    }

    /**
     * HTTP-запрос.
     *
     * @return Request
     */
    abstract protected function httpRequest(): Request;

    /**
     * Отправить запрос.
     *
     * @return array|mixed (переопределяется)
     * @throws Exception
     */
    public function send()
    {
        if (! $this->validate()) {
            throw new ValidateException($this);
        }

        $req = $this->httpRequest();
        Log::debug('Запрос: ' . $req->toString());

        $res = $req->send();
        Log::debug('Ответ: ' . $res->toString());

        if (! $res->isOk) {
            throw new Exception('HTTP-error: ' . $res->statusCode);
        }

        $res->format = Client::FORMAT_JSON;

        return $res->data;
    }
}
