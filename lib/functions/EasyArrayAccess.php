<?php

class EasyArrayAccess implements ArrayAccess
{
    /** @var array */
    private $data;

    /**
     * EasyArrayAccess constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->data;
    }
}

// this class will act as a proxy around the $CONFIG variable, returning null
// if the given offset does not exists, preventing errors and gracefully "failing"
$CONFIG = new EasyArrayAccess($CONFIG ?: []);
