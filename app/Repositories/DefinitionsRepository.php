<?php
namespace App\Repositories;

use App\Models\FormalExpression;
use App\Nayra\Contracts\RepositoryInterface;
use App\Nayra\RepositoryTrait;
use App\Repositories\ExecutionInstanceRepository;
use App\Repositories\TokenRepository;
use App\Models\DataStore;
use App\Repositories\Collaboration;

/**
 * Definitions Repository
 *
 */
class DefinitionsRepository implements RepositoryInterface
{

    use RepositoryTrait;
    
    private $tokenRepository = null;

    public function createCallActivity()
    {
        
    }

    public function createExecutionInstanceRepository()
    {
        return new ExecutionInstanceRepository();
    }

    public function createFormalExpression()
    {
        return new FormalExpression();
    }

    /**
     * Creates a TokenRepository
     *
     * @return \app\Nayra\Contracts\Repositories\TokenRepositoryInterface
     */
    public function getTokenRepository()
    {
        if ($this->tokenRepository === null) {
            $this->tokenRepository = new TokenRepository($this->createExecutionInstanceRepository());
        }
        return $this->tokenRepository;
    }
    
    public function createDataStore() {
        return new DataStore();
    }
}
