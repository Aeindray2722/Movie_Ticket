<?php
require_once __DIR__ . '/../models/VIPCustomer.php';
require_once __DIR__ . '/../models/NormalCustomer.php';

class CustomerFactory
{
    private const VIP_THRESHOLD = 50000;

    public static function create(UserModel $user, $db): CustomerTypeInterface
    {
        $userId = $user->getId();
        $total = $db->getUserMonthlyBookingTotal($userId);

        if ($total >= self::VIP_THRESHOLD) {
            $vip = new VIPCustomer();
            self::copyUserData($user, $vip);
            return $vip;
        } else {
            $normal = new NormalCustomer();
            self::copyUserData($user, $normal);
            return $normal;
        }
    }

    private static function copyUserData(UserModel $from, UserModel $to)
    {
        $to->setId($from->getId());
        $to->setName($from->getName());
        $to->setEmail($from->getEmail());
        $to->setPhone($from->getPhone());
        $to->setProfileImg($from->getProfileImg());
        $to->setRole($from->getRole());
        $to->setIsActive($from->getIsActive());
        $to->setCreatedAt($from->getCreatedAt());
        $to->setProviderToken($from->getProviderToken());
        $to->setUpdatedAt($from->getUpdatedAt());
        $to->setIsConfirmed($from->getIsConfirmed());
        $to->setIsLogin($from->getIsLogin());
    }
}
