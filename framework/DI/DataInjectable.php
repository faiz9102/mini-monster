<?php

namespace Framework\DI;

trait DataInjectable
{
    protected array $_data = [];

    /**
     * Set the data array
     */
    public function _setData(array $data): void
    {
        $this->_data = $data;
    }

    /**
     * Get data by key or return entire data array
     */
    protected function _getData(string $key = null): mixed
    {
        if ($key === null) {
            return $this->_data;
        }

        return $this->_data[$key] ?? null;
    }
}
