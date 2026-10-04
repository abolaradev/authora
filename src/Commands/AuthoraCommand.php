<?php

namespace Abolaradev\Authora\Commands;

use Illuminate\Console\Command;

class AuthoraCommand extends Command
{
    public $signature = 'authora';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
