<?php

namespace app\auth;

use yii\authclient\OAuth2;
use Yii;

/**
 * @author Sila Klnaklaeo <p_taung@hotmail.com>
 * @since 2.0
 */
class HealthIDOAuth extends OAuth2 {

    public $attributeNames = [
        'name',
        'email',
        'picture',
        'account_id',
    ];

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://moph.id.th/oauth/redirect';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://moph.id.th/api/v1/token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://provider.id.th/api/v1/services';

    /**
      /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(' ', [
                    //'account_id'
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
        $response = $this->api('token', 'POST', [
            'token_by' => 'Health ID', // Check if this is the correct parameter name & value
            'token' => $this->getAccessToken()->getToken(),
            'client_id' => Yii::$app->params['client_id'],
            'secret_key' => Yii::$app->params['secret_key'],
        ]);

        Yii::error('API Response: ' . print_r($response, true)); // Log the response
        return $response;
    }

    /**
     * @inheritdoc
     */
    protected function defaultName() {
        return 'ProviderID';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle() {
        return 'ProviderID Login';
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
            'id' => 'account_id'
        ];
    }

    protected function normalizeUserAttributes($attributes) {
        if (isset($attributes['data']['account_id'])) {
            $attributes['id'] = $attributes['data']['account_id']; // กำหนด id จาก account_id
        }

        return parent::normalizeUserAttributes($attributes);
    }

    public function byClient(ClientInterface $client) {
        $attributes = $client->getUserAttributes();
        Yii::error("Client Attributes: " . print_r($attributes, true)); // Debug

        return $this->andWhere([
                    'provider' => $client->getId(),
                    'client_id' => $attributes['id'] ?? $attributes['data']['account_id'] ?? null, // ใช้ account_id ถ้าไม่มี id
        ]);
    }

    public function fetchAccessToken($authCode, array $params = []) {
        $tokenResponse = parent::fetchAccessToken($authCode);

        if ($tokenResponse instanceof \yii\authclient\OAuthToken) {
            $accessToken = $tokenResponse->getParam('data'); // ดึง data จาก response
            if (isset($accessToken['access_token'])) {
                $tokenResponse->setParam('access_token', $accessToken['access_token']); // ตั้งค่าใหม่
            }
        }

        Yii::error("Fetched Access Token: " . print_r($tokenResponse, true));

        return $tokenResponse;
    }

}
