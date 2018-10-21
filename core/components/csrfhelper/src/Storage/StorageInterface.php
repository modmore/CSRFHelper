<?php

namespace modmore\CSRFHelper\Storage;

interface StorageInterface {
    /**
     * @param string $key
     * @return string
     */
    public function get($key);

    /**
     * @param string $key
     * @param string $token
     * @return void
     */
    public function set($key, $token);
}
