<?php

namespace Abolaradev\Authora\Services;

use Abolaradev\Authora\Enums\Feature;
use Abolaradev\Authora\Exceptions\FeatureNotEnabledException;
use Closure;

final class FeatureManager
{
    /**
     * Execute the given callback if the feature is enabled.
     *
     * @param Feature $feature The feature to check.
     * @param Closure $action The callback to execute.
     *
     * @return mixed The result of the callback.
     *
     * @throws FeatureNotEnabledException If the feature is not enabled.
     */
    public static function execute(Feature $feature , Closure $action): mixed
    {
        $featureIsEnable = in_array($feature, config('authora.features', []), true);

        if($featureIsEnable == false){
            throw new FeatureNotEnabledException($feature->name);
        }

        return $action();
    }
}