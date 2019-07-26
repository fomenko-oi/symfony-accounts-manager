<?php

namespace App\Model\Category\Account\Filter;

class Filter
{
    public $account_id;
    public $login;
    public $password;
    public $data = [];
    public $fields = [];

    public function hasFields(): bool
    {
        return count($this->data) > 0;
    }

    public function getField(int $id)
    {
        return $this->fields[$id] ?? null;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function __get($name)
    {
        if(strpos($name, 'filter_field') === false) {
            throw new \InvalidArgumentException("Unable to find {$name}");
        }

        return $this->data[$name] ?? null;
    }

    public function __set($name, $value)
    {
        if(strpos($name, 'filter_field') === false) {
            throw new \InvalidArgumentException("Unable to find {$name}");
        }

        $this->data[$name] = $value;
        $this->fields[substr($name, 13)] = $value;
    }
}
