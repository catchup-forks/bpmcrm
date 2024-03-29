<?php
namespace Tests\Feature\Shared;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Permission;

trait RequestHelper
{
    protected $user;
    protected $debug = true;
    private $_debug_response;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'password' => 'password',
            'is_administrator' => true,
        ]);

        if (method_exists($this, 'withUserSetUp')) {
            $this->withUserSetup();
        }
    }

    protected function apiCall($method, $url, $params = [])
    {
        // If the url was generated using the route() helper,
        // strip out the http://.../api/1.0 part of it;
        $url = preg_replace('/^.*\/api\/1\.0/i', '', $url);

        $response = $this->actingAs($this->user, 'api')
                         ->mediumText($method, '/api/1.0' . $url, $params);
        $this->_debug_response = $response;
        return $response;
    }

    protected function webCall($method, $url, $params = [])
    {
        $response = $this->actingAs($this->user, 'web')
                         ->call($method, $url, $params);
        $this->_debug_response = $response;
        return $response;
    }
    protected function webGet($url, $params = [])
    {
        return $this->webCall('GET', $url, $params);
    }

    public function tearDown()
    {
        parent::tearDown();
        if (!$this->debug) { return; }

        if ($this->hasFailed() && isset($this->_debug_response)) {
            try {
                $json = $this->_debug_response->mediumText();
            } catch (\Exception $e) {
                $exception = $this->_debug_response->exception;
                $json = [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTrace(),
                ];
            }
            $json['trace'] = array_slice($json['trace'], 0, 5);
            echo "\nResponse Debug Information:\n";
            var_dump($json);
            echo "\n";
        }
    }
}
