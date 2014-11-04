<?php

namespace GrimaceOfDespair;

use Composer\Script\Event;

class WpSqlSrv
{
    public static function copyPlugin(Event $event)
    {
        $composer = $event->getComposer();
        
        $muPluginPath = mapInstallerPath($composer, 'wordpress-muplugin');
            
        if (empty($muPluginPath)) {
            $muPluginPath = 'web/app/mu-plugins';
        }
        
        $webRootDir = 'web/wp';
        if ($composer->getPackage()) {
            $extra = $composer->getPackage()->getExtra();
            if (!empty($extra['webroot-dir'])) {
                $webRootDir = $extra['webroot-dir'];
            }
        }
        
        $io = $composer->getIO();
        $io->write('muplugins dir: ' . $muPluginPath);
        $io->write('webroot dir: ' . $webRootDir);
    }
    
    protected mapInstallerPath($composer, $type)
    {
        if ($composer->getPackage()) {
            $extra = $composer->getPackage()->getExtra();
            if (!empty($extra['installer-paths'])) {
                $customPath = $this->mapCustomInstallPaths($extra['installer-paths'], $type);
                if ($customPath !== false) {
                    return $this->templatePath($customPath, array());
                }
            }
        }
        
        return null;
    }
      
    /**
     * Search through a passed paths array for a custom install path.
     *
     * @param  array  $paths
     * @param  string $name
     * @param  string $type
     * @return string
     */
    protected function mapCustomInstallPaths(array $paths, $type)
    {
        foreach ($paths as $path => $names) {
            if (in_array('type:' . $type, $names)) {
                return $path;
            }
        }

        return false;
    }
    
    /**
     * Replace vars in a path
     *
     * @param  string $path
     * @param  array  $vars
     * @return string
     */
    protected function templatePath($path, array $vars = array())
    {
        if (strpos($path, '{') !== false) {
            extract($vars);
            preg_match_all('@\{\$([A-Za-z0-9_]*)\}@i', $path, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $var) {
                    $path = str_replace('{$' . $var . '}', $$var, $path);
                }
            }
        }

        return $path;
    }

}

/*
$source = "dir/dir/dir";
$dest= "dest/dir";

mkdir($dest, 0755);
foreach (
 $iterator = new RecursiveIteratorIterator(
  new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
  RecursiveIteratorIterator::SELF_FIRST) as $item
) {
  if ($item->isDir()) {
    mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
  } else {
    copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
  }
}
*/
?>