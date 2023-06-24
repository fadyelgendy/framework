<?php

namespace Lighter\Framework\Validation;

use Exception;
use Lighter\Framework\Facades\Logger;
use Lighter\Framework\Interfaces\ValidationInterface;

class Validator implements ValidationInterface
{
    const BYTE_SIZE = 8;
    protected array $errors = [];
    protected bool $valid = true;

    /**
     * Make Validation
     *
     * @param array $data
     * @param array $rules
     * @return self
     * @throws Exception
     */
    public function make(array $data, array $rules): self
    {
        foreach ($rules as $key => $value) {
            if (!is_array($value)) {
                $exception = new Exception('ERROR: validation rule must be array type');
                Logger::error($exception);
                throw $exception;
            }

            foreach ($value as $func) {
                $extraParams = null;

                if (str_contains($func, ':')) {
                    $exploded = explode(':', $func);
                    $func = $exploded[0];
                    $extraParams = $exploded[1];
                }

                # check method exists
                if (!method_exists($this, $func)) {
                    $exception = new \BadMethodCallException("ERROR: {$func} doesn't exists!");
                    Logger::error($exception);
                    throw $exception;
                }

                $this->{$func}($key, $data[$key], $extraParams);
            }
        }

        return $this;
    }

    /**
     * Check if data is valid or not.
     *
     * @return bool
     */
    public function invalid(): bool
    {
        if (!empty($this->errors)) {
            $this->valid = false;
        }

        return $this->valid == false;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function required(string $key, $value): self
    {
        $value = !is_string($value) ?: trim($value);

        if (!isset($key)) { # Value not exists
            $this->errors[$key] = "{$key} Is required!.";
        } else if (empty($value) || strlen($value) == 0) { # Value empty
            $this->errors[$key] = "{$key} Can not be empty!.";
        } else {
            unset($this->errors[$key]);
        }

        return $this;
    }

    /**
     * Minimum length
     *
     * @param string $key
     * @param $value
     * @param int $length
     * @return $this
     */
    public function min(string $key, $value, int $length): self
    {
        // Max Length
        if (strlen($value) < $length) {
            $this->errors[$key] = "{$key} Must be at least {$length} characters!";
        }

        return $this;
    }

    /**
     * Check maximum length
     *
     * @param string $key
     * @param $value
     * @param int $length
     * @return $this
     */
    public function max(string $key, $value, int $length): self
    {
        // Max Length
        if (strlen($value) < $length) {
            $this->errors[$key] = "{$key} Must not be greater than {$length} characters!";
        }

        return $this;
    }

    /**
     * Validate string, return back if error, nothing if passed
     *
     * @param string $key
     * @param $value
     * @return void
     */
    public function string(string $key, $value): void
    {
        if (!preg_match("/[a-zA-Z]/", $value)) {
            $this->errors[$key] = "{$key} is invalid!.";
        }
    }

    /**
     * Validate File
     * redirect back when error, nothing if passed
     * @param $file
     * @param float $size [in KB, like 2048 => 2MB ]
     * @param string $type
     * @param array $mimes
     * @return void
     */
    public function file($file, float $size, string $type, array $mimes): void
    {
        // File Not set
        if (!isset($file)) {
            abort($type, "File is required!");
        }

        $file_name = $file['tmp_name'];

        // is file and is uploaded via post
        if (!is_file($file_name) || !is_uploaded_file($file_name)) {
            abort($type, "File is invalid!");
        }

        $sizeInMB = $size / 1024;
        // Size Not Match
        if (filesize($file_name) > ($size * self::BYTE_SIZE)) {
            abort($type, "File size must be less than or equal to {$sizeInMB}MB!");
        }

        $file_details = $this->getFileTypeAndMime($file_name);

        // File Type
        if ($file_details['type'] != $type) {
            abort($type, "File Must be of type {$type}");
        }

        // Mime
        if (!in_array($file_details['mime'], $mimes)) {
            $mimesString = implode(',', $mimes);
            abort($type, "File must be one of {$mimesString}");
        }
    }

    /**
     * Return file type and mime
     *
     * @param string $file_name
     * @return array
     */
    protected function getFileTypeAndMime(string $file_name): array
    {
        $file_details = explode("/", mime_content_type($file_name));

        return [
            'type' => $file_details[0],
            'mime' => $file_details[1],
        ];
    }
}
