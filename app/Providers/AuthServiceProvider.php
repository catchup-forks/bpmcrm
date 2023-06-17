<?php

namespace App\Providers;

use Illuminate\Auth\RequestGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\Model\Application;
use App\Model\Screen;
use App\Model\Delegation;
use App\Model\InputDocument;
use App\Model\OutputDocument;
use App\Model\PmTable;
use App\Model\Process;
use App\Model\ProcessCategory;
use App\Model\ProcessVariable;
use App\Model\ReportTable;
use App\Model\Role;
use App\Model\Task;
use App\Model\TaskUser;
use App\Model\Script;
use App\Policies\ApplicationPolicy;
use App\Policies\AssigneeTaskPolicy;
use App\Policies\FormPolicy;
use App\Policies\InputDocumentPolicy;
use App\Policies\OutputDocumentPolicy;
use App\Policies\PmTablePolicy;
use App\Policies\ProcessCategoryPolicy;
use App\Policies\ProcessPolicy;
use App\Policies\ProcessVariablePolicy;
use App\Policies\ReportTablePolicy;
use App\Policies\TaskPolicy;
use App\Policies\ScriptPolicy;

/**
 * Our AuthService Provider binds our base processmaker provider and registers any policies, if defined.
 * @package app\Providers
 * @todo Add gates to provide authorization functionality. See branch release/3.3 for sample implementations
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
    }

}
