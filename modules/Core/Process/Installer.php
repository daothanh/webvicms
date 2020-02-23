<?php
namespace Modules\Core\Process;

use Symfony\Component\Process\Process;

class Installer extends \Nwidart\Modules\Process\Installer
{
    /**
     * Install the module via composer.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function installViaComposer()
    {
        return Process::fromShellCommandline(sprintf(
            'cd %s && composer require %s',
            realpath(base_path().'/..'),
            $this->getPackageName()
        ));
    }
}
