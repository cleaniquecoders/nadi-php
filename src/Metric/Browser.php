<?php

namespace CleaniqueCoders\Nadi\Metric;

use hisorange\BrowserDetect\Parser;

class Browser implements Contract
{
    public static function metrics(): array
    {
        $browser = (new Parser(null, request()))->detect()->toArray();
        foreach ($browser as $key => $value) {
            unset($browser[$key]);
            $key = str_replace(['browser', 'is'], '', $key);
            $key = str_replace('.', '-', $key);
            $key = str_replace(['i.e', 'in.app', 'user.agent', 'mobile.grade'], ['ie', 'in-app', 'user-agent', 'mobile-grade'], $key);
            $browser[$key] = $value;
        }

        return $browser;
    }
}
