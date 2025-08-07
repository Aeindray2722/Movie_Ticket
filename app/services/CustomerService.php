<?php
require_once __DIR__ . '/CustomerFactory.php';

class CustomerService
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function updateCustomerType(UserModel $user): bool
    {
        $customer = CustomerFactory::create($user, $this->db);
        $type = $customer->getCustomerType();
        $id = $user->getId();
        return $this->db->update('users', $id, ['customer_type' => $type]);
    }
}
