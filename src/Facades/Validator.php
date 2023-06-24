<?php

namespace Lighter\Framework\Facades;

class Validator
{
    protected \Lighter\Framework\Validation\Validator $validator;

    public function __construct() {
        $this->validator = $this->validator ?? new \Lighter\Framework\Validation\Validator();
    }

    /**
     * Make validation
     *
     * @param array $data
     * @param array $rules
     * @return \Lighter\Framework\Validation\Validator
     * @throws \Exception
     */
    public static function make(array $data, array $rules): \Lighter\Framework\Validation\Validator
    {
        return (new static())->validator->make($data, $rules);
    }
}