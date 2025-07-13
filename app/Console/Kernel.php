<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sincroniza produtos uma vez por dia à meia-noite (quando a API atualiza)
        $schedule->command('products:sync')
                ->dailyAt('00:05') // 5 minutos após meia-noite para garantir que a API já atualizou
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/sync-products.log'));
        
        // Sincronização de backup às 6h da manhã (caso a primeira falhe)
        $schedule->command('products:sync')
                ->dailyAt('06:00')
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/sync-products-backup.log'));
        
        // Comentado: sincronização a cada hora (não necessário)
        // $schedule->command('products:sync')->hourly();
        
        // Comentado: sincronização a cada 30 minutos (não necessário)
        // $schedule->command('products:sync')->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 