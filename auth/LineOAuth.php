<?php

namespace app\auth;

#namespace app\authclient\clients;

use yii\authclient\OAuth2;

/**
 * Moph allows authentication via Moph OAuth.
 *
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'app\auth\Moph',
 *         'clients' => [
 *             'moph' => [
 *                 'class' => 'yii\app\clients\MophOAuth',
 *                 'clientId' => 'google_client_id',
 *                 'clientSecret' => 'google_client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ```
 *
 *
 * @author Sila Klnaklaeo <p_taung@hotmail.com>
 * @since 2.0
 */
class LineOAuth extends OAuth2 {

    public $attributeNames = [
        'name',
        'email',
        'picture',
    ];

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://access.line.me/oauth2/v2.1/authorize';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.line.me/oauth2/v2.1/token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.line.me/v2/';

    /**
      /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(' ', [
                'profile',
                'openid',
                'email',
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function applyAccessTokenToRequest($request, $accessToken) {
        $request->getHeaders()->add('Authorization', 'Bearer ' . $accessToken->getToken());
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes() {
        return $this->api('profile', 'GET', [
                    'fields' => implode(',', $this->attributeNames),
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName() {
        return 'line';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle() {
        return 'Line Login';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 960,
            'popupHeight' => 580,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'id' => 'userId'
        ];
    }

}
