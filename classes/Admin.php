<?php

namespace Faisalcollinet\Wardrobe;

class Admin
{
    private $user_id;
    private $role;
    private $firstname;

    public function __construct($user_id, $role, $firstname)
    {
        $this->user_id = $user_id;
        $this->role = $role;
        $this->firstname = $firstname;
    }

    public static function isAdmin($role)
    {
        return $role === 'admin';
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public static function checkAdminLogin($user_id, $role)
    {
        if (!isset($user_id) || !self::isAdmin($role)) {
            header('Location: login.php');
            exit();
        }
    }
}