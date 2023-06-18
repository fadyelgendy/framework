<?php

namespace Lighter\Framework\Facades;

class Validator
{
    protected \Lighter\Framework\Validator $validator;

    public function __construct() {
        $this->validator = $this->validator ?? new \Lighter\Framework\Validator();
    }

    /**
     * Make validation
     *
     * @param array $data
     * @param array $rules
     * @return \Lighter\Framework\Validator
     * @throws \Exception
     */
    public static function make(array $data, array $rules): \Lighter\Framework\Validator
    {
        return (new static())->validator->make($data, $rules);
    }
}