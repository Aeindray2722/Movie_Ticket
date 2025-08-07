<?php
require_once __DIR__ . '/UserModel.php';
require_once __DIR__ . '/CustomerTypeInterface.php';

class NormalCustomer extends UserModel implements CustomerTypeInterface
{
    public function getCustomerType(): string
    {
        return 'Normal';
    }
}
