<?php

namespace Modules\User\Console;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Modules\User\Entities\User;
use Modules\User\Repositories\UserRepository;
use Symfony\Component\Console\Input\InputArgument;

class UserChangePassword extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'user:change-password {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the password of an user.';

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
        $email = $this->argument('email');
        $data = [
            'email' => $email,
        ];
        $rules = [
            'email' => 'required|email|exists:users,email',
        ];
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());
        } else {
            $pwd = Str::random(8);
            if ($this->changePassword($email, $pwd)) {
                $this->info("The password was changed to {$pwd}");
            } else {
                $this->info("Has an error!");
            }

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
            ['email', InputArgument::REQUIRED, 'Your email'],
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
        ];
    }

    /**
     * @param $data
     * @return mixed|User
     */
    protected function changePassword($email, $pwd)
    {
        $repo = app(UserRepository::class);
        $user = $repo->findByAttributes(['email' => $email]);
        if ($user) {
            $user->password = \Hash::make($pwd);

            $user->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
            return true;
        }
        return false;
    }
}
