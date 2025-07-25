<?php

class UserModel
{
    // ✅ Declare all private properties
    private $id;
    private $name;
    private $email;
    private $phone;
    private $profile_img;
    private $role;
    private $provider_token;
    private $is_active;
    private $password;
    private $created_at;
    private $updated_at;
    private $is_login;
    private $is_confirmed;

    // ✅ Setters & Getters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    public function getPhone()
    {
        return $this->phone;
    }

    public function setProfileImg($profile_img)
    {
        $this->profile_img = $profile_img;
    }
    public function getProfileImg()
    {
        return $this->profile_img;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }
    public function getRole()
    {
        return $this->role;
    }

    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }
    public function getIsActive()
    {
        return $this->is_active;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setProviderToken($provider_token)
    {
        $this->provider_token = $provider_token;
    }
    public function getProviderToken()
    {
        return $this->provider_token;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setIsConfirmed($is_confirmed)
    {
        $this->is_confirmed = $is_confirmed;
    }
    public function getIsConfirmed()
    {
        return $this->is_confirmed;
    }

    public function setIsLogin($is_login)
    {
        $this->is_login = $is_login;
    }
    public function getIsLogin()
    {
        return $this->is_login;
    }

    // ✅ Convert all to array
    public function toArray()
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "email" => $this->getEmail(),
            "password" => $this->getPassword(),
            "phone" => $this->getPhone(),
            "profile_img" => $this->getProfileImg(),
            "is_active" => $this->getIsActive(),
            "role" => $this->getRole(),
            "created_at" => $this->getCreatedAt(),
            "provider_token" => $this->getProviderToken(),
            "updated_at" => $this->getUpdatedAt(),
            "is_confirmed" => $this->getIsConfirmed(),
            "is_login" => $this->getIsLogin()
        ];
    }
}
