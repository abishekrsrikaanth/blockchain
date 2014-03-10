<?php
namespace BlockChain;

use Guzzle\Http\Client;

class Wallet extends Base
{
    private $_api_url = 'https://blockchain.info/api/v2/';
    private $_merchant_url = 'https://blockchain.info/merchant/';
    private $_client;

    public function __construct()
    {
        $this->_client = new Client();
    }

    /**
     * Create a new blockchain.info bitcoin wallet.
     * It can be created containing a pre-generated private key or will otherwise generate a new private key.
     *
     * @param string $api_code API code with create wallets permission.
     * @param string $password The password for the new wallet. Must be at least 10 characters in length.
     * @param string $private_key A private key to add to the wallet (Wallet import format preferred).
     * @param string $label A label to set for the first address in the wallet. Alphanumeric only.
     * @param string $email An email to associate with the new wallet i.e. the email address of the user you are creating this wallet on behalf of.
     * @return string
     */
    public function create($api_code, $password, $private_key = "", $label = "", $email = "")
    {
        $payload = array(
            'password' => $password,
            'api_code' => $api_code
        );

        if (!empty($private_key)) {
            $payload['priv'] = $private_key;
        }

        if (!empty($label)) {
            $payload['label'] = $label;
        }

        if (!empty($email)) {
            $payload['email'] = $email;
        }

        $request = $this->_client->post($this->_api_url . 'create_wallet', null, $payload);
        return $this->_send($request);

    }

    /**
     * Fetch the balance of a wallet. This should be used as an estimate only and will include unconfirmed transactions and possibly double spends.
     * 
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     * @return array|bool|float|int|string
     */
    public function getBalance($wallet_id, $main_password)
    {
        $request = $this->_client->get($this->_merchant_url . $wallet_id . '/balance?password=' . $main_password);
        return $this->_send($request);
    }
} 
