<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Storage
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return mixed
     */
    public function set(string $name, $value)
    {
        return $this->session->set($name, $value);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->session->get($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name)
    {
        return $this->session->has($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function remove(string $name)
    {
        return $this->session->remove($name);
    }
}