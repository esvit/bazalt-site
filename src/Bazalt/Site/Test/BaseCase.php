<?php

namespace Bazalt\Site\Test;

use Bazalt\Rest;

abstract class BaseCase extends \Bazalt\Rest\Test\BaseCase
{
    protected $site = null;

    protected function setUp(): void
    {
        $this->site = \Bazalt\Site\Model\Site::create();
        $this->site->secret_key = uniqid();
        $this->site->save();

        \Bazalt\Site::setCurrent($this->site);
    }

    protected function tearDown(): void
    {
        if ($this->site->id) {
            $this->site->delete();
        }
        $this->site = null;
        \Bazalt\Site::setCurrent(null);
    }
}
