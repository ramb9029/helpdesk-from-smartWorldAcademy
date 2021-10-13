<?php

namespace App\Providers;

use App\Events\Orders\CreatingOrdersEvent;
use App\Events\Orders\UpdatingOrdersEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            CreatingOrdersEvent::class,
            UpdatingOrdersEvent::class,
        ],
        'App\Events\NotificationEvent' => ['App\Listeners\MailNotificationListener', ],
        'App\Events\Orders\CreatingOrdersEvent' => ['App\Listeners\Orders\CreatingOrdersListener', ],
        'App\Events\Orders\UpdatingOrdersEvent' => ['App\Listeners\Orders\UpdatingOrdersListener', ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
