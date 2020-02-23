<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Modules\Core\Console\Installers\Writers\EnvFileWriter;
use Modules\User\Repositories\UserRepository;
use PDOException;

class InstallController extends Controller
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var EnvFileWriter
     */
    protected $env;

    public function __construct(Config $config, EnvFileWriter $env)
    {
        $this->env = $env;
        $this->config = $config;
    }

    public function index(Request $request)
    {
        if ($request->isMethod("POST")) {
            $data = $request->all();
            $rules = [
                'app_name' => 'required',
                'app_url' => 'required',
                'db_host' => 'required',
                'db_port' => 'required',
                'db_database' => 'required',
                'db_user' => 'required',
                'db_password' => 'required',
                'user_name' => 'required',
                'user_email' => 'required',
                'user_password' => 'required',
            ];
            $validator = \Validator::make($data, $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->messages())->withInput();
            }
            $databaseConfigs = [
                'db_driver' => 'mysql',
                'db_host' => $data['db_host'],
                'db_port' => $data['db_port'],
                'db_user' => $data['db_user'],
                'db_password' => $data['db_password'],
                'db_database' => $data['db_database']
            ];
            $this->setLaravelConfiguration($databaseConfigs);
            if (!$this->databaseConnectionIsValid()) {
                return redirect()->back()->withErrors(['db_user' => 'Thông tin kết nối cơ sở dữ liệu không đúng!'])->withInput();
            }
            $this->env->write($databaseConfigs);

            // Generate app_key
            $appKey = $this->generateRandomKey();
            $this->writeNewEnvironmentFileWith($appKey);

            // Run migrate
            Artisan::call('migrate');
            $modules = [
                'Core',
                'Media',
                'Page',
                'Tag',
                'User',
            ];
            foreach ($modules as $module) {
                Artisan::call('module:migrate', ['module' => $module]);
            }

            //Run seeders
            foreach ($modules as $module) {
                Artisan::call('module:seed', ['module' => $module]);
            }

            // Create Admin User
            $userData = [
                'name' => $data['user_name'],
                'email' => $data['user_email'],
                'password' => $data['user_password']
            ];
            $this->createUser($userData);
            $this->env->write(['app_installed' => 'true', 'app_name' => "{$data['app_name']}", 'app_url' => $data['app_url']]);
            return redirect()->route('home');
        }
        $this->seo()->setTitle('Cài đặt ứng dụng');
        return view('core::install.index');
    }

    protected function generateRandomKey()
    {
        return 'base64:' . base64_encode(
                Encrypter::generateKey(config('app.cipher'))
            );
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param string $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        file_put_contents(app()->environmentFilePath(), preg_replace(
            $this->keyReplacementPattern(),
            'APP_KEY=' . $key,
            file_get_contents(app()->environmentFilePath())
        ));
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote('=' . config('app.key'), '/');

        return "/^APP_KEY{$escaped}/m";
    }

    /**
     * Is the database connection valid?
     * @return bool
     */
    protected function createUser($data)
    {
        $data = array_merge($data, [
            'email_verified_at' => now(),
            'activated' => 1,
            'password' => \Hash::make($data['password'])
        ]);
        $user = app(UserRepository::class)->create($data);

        $user->assignRole('admin');
        return $user;
    }
    /**
     * @param array $vars
     */
    protected function setLaravelConfiguration($vars)
    {
        $driver = $vars['db_driver'];

        $this->config['database.default'] = $driver;
        $this->config['database.connections.' . $driver . '.host'] = $vars['db_host'];
        $this->config['database.connections.' . $driver . '.port'] = $vars['db_port'];
        $this->config['database.connections.' . $driver . '.database'] = $vars['db_database'];
        $this->config['database.connections.' . $driver . '.username'] = $vars['db_user'];
        $this->config['database.connections.' . $driver . '.password'] = $vars['db_password'];

        app(DatabaseManager::class)->purge($driver);
        app(ConnectionFactory::class)->make($this->config['database.connections.' . $driver], $driver);
    }

    /**
     * Is the database connection valid?
     * @return bool
     */
    protected function databaseConnectionIsValid()
    {
        try {
            app('db')->reconnect()->getPdo();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}