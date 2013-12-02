<?php

namespace BlockChain;

use Guzzle\Http\Client;

class Address extends Base
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
     * Create a new Bitcoin address
     * @param string $label An optional label to attach to this address. It is recommended this is a human readable string.
     * @param string $second_password Your second My Wallet password if double encryption is enabled.
     * @return string
     */
    public function createAddress($label, $second_password = "")
    {
        $data = array(
            'password' => $this->_main_password,
            'label' => $label
        );

        if (!empty($second_password)) {
            $data['second_password'] = $second_password;
        }

        $request = $this->_client->post($this->_merchant_url . $this->_wallet_id . '/new_address', null, $data);
        return $this->_send($request);
    }

    /**
     * List all active addresses in a wallet.
     * Also includes a 0 confirmation balance which should be used as an estimate only and will include unconfirmed transactions and possibly double spends.
     * @return string
     */
    public function listAddress()
    {
        $request = $this->_client->get($this->_merchant_url . $this->_wallet_id . '/list?password=' . $this->_main_password);
        return $this->_send($request);
    }

    /**
     * Retrieve the balance of a bitcoin address.
     *
     * @param string $address The bitcoin address to lookup
     * @param int $confirmations Minimum number of confirmations required. By default 0 for unconfirmed.
     * @return string
     */
    public function getBalance($address, $confirmations = 0)
    {
        $data = array(
            'password' => $this->_main_password,
            'address' => $address,
            'confirmations' => $confirmations
        );
        $request = $this->_client->post($this->_merchant_url . $this->_wallet_id . '/address_balance', null, $data);
        return $this->_send($request);
    }

    /**
     * To improve wallet performance addresses which have not been used recently should be moved to an archived state.
     * They will still be held in the wallet but will no longer be included in the "list" or "list-transactions" calls.
     *
     * @param string $address The bitcoin address to archive
     * @param string $second_password Your second My Wallet password if double encryption is enabled.
     * @return string
     */
    public function archiveAddress($address, $second_password = "")
    {
        $data = array(
            'password' => $this->_main_password,
            'address' => $address
        );

        if (!empty($second_password)) {
            $data['second_password'] = $second_password;
        }

        $request = $this->_client->post($this->_merchant_url . $this->_wallet_id . '/archive_address', null, $data);
        return $this->_send($request);
    }

    /**
     * UnArchive an address. Will also restore consolidated addresses
     *
     * @param string $address The bitcoin address to unarchive
     * @param string $second_password Your second My Wallet password if double encryption is enabled.
     * @return string
     */
    public function unArchiveAddress($address, $second_password = "")
    {
        $data = array(
            'password' => $this->_main_password,
            'address' => $address
        );

        if (!empty($second_password)) {
            $data['second_password'] = $second_password;
        }

        $request = $this->_client->post($this->_merchant_url . $this->_wallet_id . '/unarchive_address', null, $data);
        return $this->_send($request);
    }

    /**
     * Queries to wallets with over 10 thousand addresses will become sluggish especially in the web interface.
     * The auto_consolidate command will remove some inactive archived addresses from the wallet and insert them as forwarding addresses (see receive payments API).
     * You will still receive callback notifications for these addresses however they will no longer be part of the main wallet and will be stored server side.
     *
     * @param int $days Addresses which have not received any transactions in at least this many days will be consolidated.
     * @param string $second_password Your second My Wallet password if double encryption is enabled
     * @return string
     */
    public function consolidateAddress($days, $second_password = "")
    {
        $data = array(
            'password' => $this->_main_password,
            'days' => $days
        );

        if (!empty($second_password)) {
            $data['second_password'] = $second_password;
        }

        $request = $this->_client->post($this->_merchant_url . $this->_wallet_id . '/auto_consolidate', null, $data);
        return $this->_send($request);
    }
} 