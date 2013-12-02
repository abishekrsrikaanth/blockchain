<?php

namespace BlockChain;


class BlockChain
{
    /**
     * Create a new blockchain.info bitcoin wallet.
     * It can be created containing a pre-generated private key or will otherwise generate a new private key.
     * @param string $api_code API code with create wallets permission.
     * @param string $password The password for the new wallet. Must be at least 10 characters in length.
     * @param string $private_key A private key to add to the wallet (Wallet import format preferred).
     * @param string $label A label to set for the first address in the wallet. Alphanumeric only.
     * @param string $email An email to associate with the new wallet i.e. the email address of the user you are creating this wallet on behalf of.
     * @return string
     */
    public static function CreateWallet($api_code, $password, $private_key = "", $label = "", $email = "")
    {
        $instance = new Wallet();
        return $instance->create($api_code, $password, $private_key, $label, $email);
    }

    /**
     * Fetch the balance of a wallet. This should be used as an estimate only and will include unconfirmed transactions and possibly double spends.
     *
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     * @return string
     */
    public static function GetWalletBalance($wallet_id, $main_password)
    {
        $instance = new Wallet();
        return $instance->getBalance($wallet_id, $main_password);
    }

    /**
     * List all active addresses in a wallet.
     * Also includes a 0 confirmation balance which should be used as an estimate only and will include unconfirmed transactions and possibly double spends.
     *
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     * @return string
     */
    public static function ListAddress($wallet_id, $main_password)
    {
        $instance = new Address($wallet_id, $main_password);
        return $instance->listAddress();
    }

    /**
     * Create a new Bitcoin address
     *
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     * @param string $label An optional label to attach to this address. It is recommended this is a human readable string.
     * @param string $second_password Your second My Wallet password if double encryption is enabled.
     * @return string
     */
    public static function CreateAddress($wallet_id, $main_password, $label, $second_password = "")
    {
        $instance = new Address($wallet_id, $main_password);
        return $instance->createAddress($second_password, $label);
    }

    /**
     * Retreive the balance of a bitcoin address.
     *
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     * @param string $address The bitcoin address to lookup
     * @param int $confirmations Minimum number of confirmations required. By default 0 for unconfirmed.
     * @return string
     */
    public static function GetAddressBalance($wallet_id, $main_password, $address, $confirmations)
    {
        $instance = new Address($wallet_id, $main_password);
        return $instance->getBalance($address, $confirmations);
    }

    /**
     * To improve wallet performance addresses which have not been used recently should be moved to an archived state.
     * They will still be held in the wallet but will no longer be included in the "list" or "list-transactions" calls.
     *
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     * @param string $address The bitcoin address to unarchive
     * @param string $second_password Your second My Wallet password if double encryption is enabled.
     * @return string
     */
    public static function ArchiveAddress($wallet_id, $main_password, $address, $second_password)
    {
        $instance = new Address($wallet_id, $main_password);
        return $instance->archiveAddress($second_password, $address);
    }

    /**
     * UnArchive an address. Will also restore consolidated addresses
     *
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     * @param string $address The bitcoin address to unarchive
     * @param string $second_password Your second My Wallet password if double encryption is enabled.
     * @return string
     */
    public static function UnArchiveAddress($wallet_id, $main_password, $address, $second_password)
    {
        $instance = new Address($wallet_id, $main_password);
        return $instance->unArchiveAddress($second_password, $address);
    }

    /**
     * Queries to wallets with over 10 thousand addresses will become sluggish especially in the web interface.
     * The auto_consolidate command will remove some inactive archived addresses from the wallet and insert them as forwarding addresses (see receive payments API).
     * You will still receive callback notifications for these addresses however they will no longer be part of the main wallet and will be stored server side.
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     * @param int $days Addresses which have not received any transactions in at least this many days will be consolidated.
     * @param string $second_password Your second My Wallet password if double encryption is enabled
     * @return string
     */
    public static function ConsolidateAddress($wallet_id, $main_password, $days, $second_password)
    {
        $instance = new Address($wallet_id, $main_password);
        return $instance->consolidateAddress($second_password, $days);
    }

    /**
     * Static function to send bitcoin from your wallet to another bitcoin address.
     *
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     * @param string $from Send from a specific Bitcoin Address
     * @param string $to Recipient Bitcoin Address
     * @param float $amount Amount to send in satoshi
     * @param bool $shared "true" or "false" indicating whether the transaction should be sent through a shared wallet. Fees apply.
     * @param string $fee Transaction fee value in satoshi (Must be greater than default fee)
     * @param string $note A public note to include with the transaction (Optional)
     * @param string $second_password Your second My Wallet password if double encryption is enabled
     * @return string
     */
    public static function Pay($wallet_id, $main_password, $from, $to, $amount, $shared = false, $fee, $note, $second_password = "")
    {
        $instance = new Payments($wallet_id, $main_password);
        return $instance->send($from, $to, $amount, $shared, $fee, $note, $second_password);
    }

    /**
     * Static function to send a transaction to multiple recipients in the same transaction.
     *
     * @param string $wallet_id Wallet Identifier used to Login
     * @param string $main_password Your Main My wallet password
     * @param string $from Send from a specific Bitcoin Address
     * @param array $recipients Bitcoin Addresses as keys and the amounts to send as values
     * @param float $amount Amount to send in satoshi
     * @param bool $shared "true" or "false" indicating whether the transaction should be sent through a shared wallet. Fees apply.
     * @param string $fee Transaction fee value in satoshi (Must be greater than default fee)
     * @param string $note A public note to include with the transaction (Optional)
     * @param string $second_password Your second My Wallet password if double encryption is enabled
     * @return string
     */
    public static function PayToMany($wallet_id, $main_password, $from, array $recipients, $amount, $shared = false, $fee, $note, $second_password = "")
    {
        $instance = new Payments($wallet_id, $main_password);
        return $instance->send($from, $recipients, $amount, $shared, $fee, $note, $second_password);
    }

} 