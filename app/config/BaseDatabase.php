<?php

abstract class BaseDatabase
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
}
