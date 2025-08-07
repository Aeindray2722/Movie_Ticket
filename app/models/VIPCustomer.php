<?php
require_once __DIR__ . '/UserModel.php';
require_once __DIR__ . '/CustomerTypeInterface.php';

class VIPCustomer extends UserModel implements CustomerTypeInterface
{
    public function getCustomerType(): string
    {
        return 'VIP';
    }
}
