<?php

namespace Interfaces;

interface ICache{

    public function put($name, $data);

    public function get($name);

    public function isExpired($name, $ttl);

    public function delete($name);

    public function destroy();
}
