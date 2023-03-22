<?php
declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Server;
use ApiPlatform\OpenApi\OpenApi;

final class OpenApiFactory implements OpenApiFactoryInterface
{
    /**
     * @param OpenApiFactoryInterface $decorated Factory
     * @param array $appServers App servers
     */
    public function __construct(private OpenApiFactoryInterface $decorated, private array $appServers)
    {
    }

    /**
     * @param array $context Context
     * @return OpenApi
     */
    public function __invoke(array $context = []): OpenApi
    {
        $decorated = $this->decorated;
        $openApi = $decorated($context);
        $servers = array_map(fn ($server) => new Server($server), $this->appServers);
        $openApi = $openApi->withServers($servers);

        return $openApi;
    }
}
