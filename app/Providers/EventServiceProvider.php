<?php

namespace App\Providers;

use App\Models\Classroom;
use App\Models\School;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        $school = null;
        $permission_feedback = null;

        if (Schema::hasTable('schools')) {
            $school = School::first();
        }

        if (Schema::hasTable('permissions')) {
            $permission_feedback = Permission::where('name', 'active_feedback')->first();
        }

        view()->share('school', $school);
        view()->share('permission_feedback', $permission_feedback);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
