<?php

class EasyArrayAccess implements ArrayAccess
{
    /** @var array */
    private $configuration;

    /**
     * AllowingConfigurationAccess constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration = [])
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->configuration[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->configuration[$offset] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->configuration[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->configuration[$offset]);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->configuration;
    }
}

// this class will act as a proxy around the $CONFIG variable, returning null
// if the given offset does not exists, preventing errors and gracefully "failing"
$CONFIG = new EasyArrayAccess($CONFIG ?: []);
