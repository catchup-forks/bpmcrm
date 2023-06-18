<?php
namespace App\Repositories;

use App\Models\FormalExpression;
use App\Nayra\Contracts\RepositoryInterface;
use App\Nayra\RepositoryTrait;
use App\Repositories\ExecutionInstanceRepository;
use App\Repositories\TokenRepository;
use App\Models\DataStore;

/**
 * Definitions Repository
 *
 */
final class DefinitionsRepository implements RepositoryInterface
{

    use RepositoryTrait;
    
    private ?TokenRepository $tokenRepository = null;

    public function createExecutionInstanceRepository(): ExecutionInstanceRepository
    {
        return new ExecutionInstanceRepository();
    }

    public function createFormalExpression(): FormalExpression
    {
        return new FormalExpression();
    }

    /**
     * Creates a TokenRepository
     *
     * @return app\Nayra\Contracts\Repositories\TokenRepositoryInterface|null
     */
    public function getTokenRepository(): ?TokenRepository
    {
        if ($this->tokenRepository === null) {
            $this->tokenRepository = new TokenRepository($this->createExecutionInstanceRepository());
        }
        return $this->tokenRepository;
    }
    
    public function createDataStore(): DataStore {
        return new DataStore();
    }
}
