<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Sleep;

class ReloadCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reload-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reload caches.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call(command: 'view:cache');
        $this->call(command: 'route:cache');
        $this->call(command: 'optimize');
        $this->call(command: 'config:cache');
        $this->call(command: 'schedule:clear-cache');
        $this->call(command: 'event:cache');
        // 使用 Sleep 代替 php 原生 sleep 函数
        Sleep::for(2)->second();
        $this->info(string: 'Successfully reload caches.');
    }
}
