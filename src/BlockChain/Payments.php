<?php

namespace BlockChain;

use Guzzle\Http\Client;

class Payments extends Base
{
    private $_merchant_url = 'https://blockchain.info/merchant/';
    private $_client;
    private $_wallet_id;
    private $_main_password;


    /**
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     */
    public function __construct($wallet_id, $main_password)
    {
        $this->_client = new Client();
        $this->_wallet_id = $wallet_id;
        $this->_main_password = $main_password;
    }

    /**
     * Send bitcoin from your wallet to another bitcoin address.
     *
     * @param string $from Send from a specific Bitcoin Address
     * @param string $to Recipient Bitcoin Address
     * @param float $amount Amount to send in satoshi
     * @param bool $shared "true" or "false" indicating whether the transaction should be sent through a shared wallet. Fees apply.
     * @param string $fee Transaction fee value in satoshi (Must be greater than default fee)
     * @param string $note A public note to include with the transaction (Optional)
     * @param string $second_password Your second My Wallet password if double encryption is enabled
     * @return string
     */
    public function send($from, $to, $amount, $shared = false, $fee = "", $note = "", $second_password = "")
    {
        $data = array(
            'password' => $this->_main_password,
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'shared' => $shared
        );

        if (!empty($fee)) {
            $data['fee'] = $fee;
        }

        if (!empty($note)) {
            $data['note'] = $note;
        }

        if (!empty($second_password)) {
            $data['second_password'] = $second_password;
        }

        $request = $this->_client->post($this->_merchant_url . $this->_wallet_id . '/payment', null, $data);
        return $this->_send($request);
    }

    /**
     * Send a transaction to multiple recipients in the same transaction.
     *
     * @param string $from Send from a specific Bitcoin Address
     * @param array $recipients Bitcoin Addresses as keys and the amounts to send as values
     * @param float $amount Amount to send in satoshi
     * @param bool $shared "true" or "false" indicating whether the transaction should be sent through a shared wallet. Fees apply.
     * @param string $fee Transaction fee value in satoshi (Must be greater than default fee)
     * @param string $note A public note to include with the transaction (Optional)
     * @param string $second_password Your second My Wallet password if double encryption is enabled
     * @return string
     */
    public function sendToMany($from, array $recipients, $amount, $shared = false, $fee = "", $note = "", $second_password = "")
    {
        $data = array(
            'password' => $this->_main_password,
            'from' => $from,
            'recipients' => json_encode($recipients),
            'amount' => $amount,
            'shared' => $shared
        );

        if (!empty($fee)) {
            $data['fee'] = $fee;
        }

        if (!empty($noe)) {
            $data['note'] = $note;
        }

        if (!empty($second_password)) {
            $data['second_password'] = $second_password;
        }

        $request = $this->_client->post($this->_merchant_url . $this->_wallet_id . '/sendmany', null, $data);
        return $this->_send($request);
    }
} 
