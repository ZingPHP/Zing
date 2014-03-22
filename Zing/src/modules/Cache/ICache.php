<?php

namespace Modules\Cache;

interface ICache{

    public function put($name, $data, $ttl);

    public function get($name);

    public function isExpired($name, $ttl);

    public function delete($name);

    public function destroy();
}
