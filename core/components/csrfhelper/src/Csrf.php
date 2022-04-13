<?php

namespace modmore\CSRFHelper;

use modmore\CSRFHelper\Storage\StorageInterface;
use MODX\Revolution\modUser;

class Csrf {
    /**
     * @var StorageInterface
     */
    protected $storage;
    protected $user;

    /**
     * @param StorageInterface $storage
     * @param \modUser|modUser $user
     */
    public function __construct(StorageInterface $storage, $user)
    {
        $this->storage = $storage;

        if (!($user instanceof modUser || $user instanceof \modUser)) {
            throw new \InvalidArgumentException('Expected modUser, got ' . get_class($user));
        }
        $this->user = $user;
    }

    public function get($key)
    {
        $token = $this->storage->get($key);
        if ($token === false) {
            $token = $this->generate($key);
        }

        if ($this->isExpired($token)) {
            $token = $this->generate($key);
        }

        return $token;
    }

    public function generate($key)
    {
        $parts = [];

        // Add the generation time
        $parts[] = time();

        // Add the user ID if the user is logged in
        if ($this->user->get('id') > 0) {
            $parts[] = $this->user->get('id');
        }

        // Pad with a bunch of random bytes
        $parts[] = bin2hex(random_bytes(64));

        // Merge the different parts
        $token = implode('--', $parts);
        $token = base64_encode($token);

        $this->storage->set($key, $token);

        return $token;
    }

    public function check($key, $token)
    {
        $expected = $this->storage->get($key);
        if ($expected !== $token) {
            throw new InvalidTokenException('Token does not match');
        }

        if ($this->isExpired($token)) {
            throw new InvalidTokenException('Token has expired');
        }
    }

    private function isExpired($token)
    {
        $token = base64_decode($token);
        $token = explode('--', $token);
        $timestamp = reset($token);

        // Make sure we have a timestamp
        if (!is_numeric($timestamp)) {
            return true;
        }

        // Make sure it's no older than 1 day
        $ttl = 24 * 60 * 60;
        if ($timestamp + $ttl < time()) {
            return true;
        }

        // And also isn't in the future
        if ($timestamp > time()) {
            return true;
        }

        return false;
    }
}
