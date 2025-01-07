<?php

namespace StackSite\Core;

class RequestBodyHandler
{
    private array $data = [];

    public function loadBody(): void
    {
        if ($this->data !== []) {
            return;
        }

        $decodedData = json_decode(file_get_contents('php://input'), true);

        if ($decodedData !== null) {
            $this->data = $decodedData;
            return;
        }

        $this->data = [];
    }

    public function get($key)
    {
        self::loadBody();
        return $this->data[$key] ?? null;
    }

    public function getAll($keys = []): ?array
    {
        $this->loadBody();

        $temp = [];

        if ($keys === []) {
            $temp = $this->data;
        }

        foreach ($keys as $key) {
            if (isset($this->data[$key])) {
                $temp[$key] = $this->data[$key];
            }
        }

        if (isset($temp['secret'])) {
            unset($temp['secret']);
        }

        return $temp ?? null;
    }

    public function checkFilledBody(array $data): bool
    {
        $this->loadBody();

        foreach ($data as $key => $peace) {
            if ($peace === null) {
                $wrongKeys[] = $key;
            }
        }

        return empty($wrongKeys);
    }
}