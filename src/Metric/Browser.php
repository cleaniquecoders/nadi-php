<?php

namespace CleaniqueCoders\Nadi\Metric;

use CleaniqueCoders\Nadi\Support\Arr;
use hisorange\BrowserDetect\Parser;
use Illuminate\Support\Str;

class Browser extends Base
{
    public function metrics(): array
    {
        $browser = (new Parser(null, request()))->detect()->toArray();
        foreach ($browser as $key => $value) {
            unset($browser[$key]);
            $key = str_replace(['browser', 'is'], '', $key);
            $key = Str::snake($key, '.');
            $key = str_replace(['i.e', 'in.app', 'user.agent', 'mobile.grade'], ['ie', 'in-app', 'user-agent', 'mobile-grade'], $key);
            $browser[$key] = $value;
        }

        return [
            'browser' => Arr::undot($browser),
        ];
    }
}
