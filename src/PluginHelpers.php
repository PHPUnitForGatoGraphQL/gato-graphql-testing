<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting;

use PoP\Root\Environment as RootEnvironment;

class PluginHelpers
{
    public static function enablePlugin(): bool
    {
        if (RootEnvironment::isApplicationEnvironmentDev()) {
            return true;
        }

        /**
         * @var string|null
         * phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
         */
        $httpHost = $_SERVER['HTTP_HOST'] ?? null;
        if ($httpHost === null) {
            return false;
        }

        $domain = static::getDomainFromHTTPHost($httpHost);
        $validTestingDomains = array_merge(
            Environment::getContinuousIntegrationValidTestingDomains(),
            Environment::getLocalDevelopmentValidTestingDomains()
        );
        return in_array($domain, $validTestingDomains);
    }

    /**
     * Calculate the top level domain (app.site.com => site.com)
     */
    protected static function getDomainFromHTTPHost(string $httpHost): string
    {
        $hostNames = array_reverse(explode('.', $httpHost));
        return $hostNames[1] . '.' . $hostNames[0];
    }
}
