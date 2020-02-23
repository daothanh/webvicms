<?php

namespace Modules\User\Console;

use Illuminate\Console\Command;
use Modules\User\Repositories\UserRepository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeUserToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user-token {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make token for an user';

    protected $user;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepository $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = $this->user->findByAttributes(['email' => $this->argument('email')]);
        if (!$user) {
            $this->info("Email ".$this->argument('email')." không tồn tại!");
            return false;
        }
        $this->user->generateTokenFor($user);
    }
}
