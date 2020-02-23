<?php

namespace Modules\Core\Process;

use Nwidart\Modules\Module;

class Updater extends \Nwidart\Modules\Process\Updater
{
    /**
     * Update the dependencies for the specified module by given the module name.
     *
     * @param string $module
     */
    public function update($module)
    {
        $module = $this->module->findOrFail($module);

        chdir(realpath(base_path().DIRECTORY_SEPARATOR.'..'));

        $this->installRequires($module);
        $this->installDevRequires($module);
        $this->copyScriptsToMainComposerJson($module);
    }

    /**
     * @param Module $module
     */
    private function installRequires(Module $module)
    {
        $packages = $module->getComposerAttr('require', []);

        $concatenatedPackages = '';
        foreach ($packages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if (!empty($concatenatedPackages)) {
            $this->run("composer require {$concatenatedPackages}");
        }
    }

    /**
     * @param Module $module
     */
    private function installDevRequires(Module $module)
    {
        $devPackages = $module->getComposerAttr('require-dev', []);

        $concatenatedPackages = '';
        foreach ($devPackages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if (!empty($concatenatedPackages)) {
            $this->run("composer require --dev {$concatenatedPackages}");
        }
    }

    /**
     * @param Module $module
     */
    private function copyScriptsToMainComposerJson(Module $module)
    {
        $scripts = $module->getComposerAttr('scripts', []);
        $composerFilePath = realpath(base_path().DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR.'composer.json';
        $composer = json_decode(file_get_contents($composerFilePath), true);

        foreach ($scripts as $key => $script) {
            if (array_key_exists($key, $composer['scripts'])) {
                $composer['scripts'][$key] = array_unique(array_merge($composer['scripts'][$key], $script));
                continue;
            }
            $composer['scripts'] = array_merge($composer['scripts'], [$key => $script]);
        }

        file_put_contents($composerFilePath, json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
