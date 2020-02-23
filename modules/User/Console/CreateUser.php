<?php

namespace Modules\User\Console;

use Illuminate\Console\Command;
use Modules\User\Entities\User;
use Modules\User\Repositories\UserRepository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateUser extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'user:create {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = [
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => $this->argument('password')];
        $role = $this->option('role');
        if (!$role) {
            $role = 'user';
        }
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ];
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());
        } else {
            $user = $this->createUser($data);
            if ($user && $role) {
                $user->assignRole($role);
            }
            $this->info('User was created!');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Your name'],
            ['email', InputArgument::REQUIRED, 'Your email'],
            ['password', InputArgument::REQUIRED, 'Your password'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['role', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

    /**
     * @param $data
     * @return mixed|User
     */
    protected function createUser($data)
    {
        $data = array_merge($data, [
            'email_verified_at' => now(),
            'activated' => 1,
            'password' => \Hash::make($data['password'])
        ]);
        return app(UserRepository::class)->create($data);
    }
}
