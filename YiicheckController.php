<?php
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;

class YiicheckController extends Controller
{
    public function actionUrl($website, $resfile=false, $output=true) {
        //first connect http without www:
        $website = $this->clearhttps($website);
        $website = $this->clearwww($website);
        $website = $this->sethttp($website);
        if ($output) {
            $url = $this->ansiFormat($website, BaseConsole::FG_CYAN);
            echo '    #1 Checking website By Url '.$url.PHP_EOL;
        }
        $content = @file_get_contents($website);

        //second connect https without www:
        if (!$content) {
            $website = $this->clearhttps($website);
            $website = $this->clearwww($website);
            $website = $this->sethttps($website);
            if ($output) {
                $url = $this->ansiFormat($website, BaseConsole::FG_CYAN);
                echo '    #2 Checking website By Url '.$url.PHP_EOL;
            }
            $content = @file_get_contents($website);
        }

        //third connect http with www:
        if (!$content) {
            $website = $this->clearhttps($website);
            $website = $this->clearwww($website);
            $website = $this->setwww($website);
            $website = $this->sethttp($website);
            if ($output) {
                $url = $this->ansiFormat($website, BaseConsole::FG_CYAN);
                echo '    #3 Checking website By Url '.$url.PHP_EOL;
            }
            $content = @file_get_contents($website);
        }

        //fourth connect https with www:
        if (!$content) {
            $website = $this->clearhttps($website);
            $website = $this->clearwww($website);
            $website = $this->setwww($website);
            $website = $this->sethttps($website);
            if ($output) {
                $url = $this->ansiFormat($website, BaseConsole::FG_CYAN);
                echo '    #4 Checking website By Url '.$url.PHP_EOL;
            }
            $content = @file_get_contents($website);
        }
        
        // if can't parse:
        if (!$content && $output) {
            $error = $this->ansiFormat('    Can\'t parse the website', BaseConsole::FG_PURPLE);
            echo $error.PHP_EOL;
            return;
        }

        // if parsed ok, than check for yii:
        $result = $this->check($content);
        if ($output) {
            if ($result) {
                $result = $this->ansiFormat('    The website is based on Yii framework'.PHP_EOL.PHP_EOL, BaseConsole::FG_GREEN);
                if ($resfile && file_exists($resfile)) {
                    $data = file_get_contents($resfile);
                    $data .= $website.PHP_EOL;
                    file_put_contents($resfile, $data);
                }
            } else {
                $result = $this->ansiFormat('    Nothing about Yii was detected'.PHP_EOL.PHP_EOL, BaseConsole::FG_RED);
            }
            echo $result;
        }
        return ExitCode::OK;
    }

    public function actionFile($file, $resfile=false, $output=true) {

        if ($file) {
            if (!file_exists($file)) {
                $error = $this->ansiFormat('Can\'t open the file', BaseConsole::FG_PURPLE);
                echo $error; die();
            }
        }

        if ($resfile) {
            if (file_exists($resfile)) {
                $error = $this->ansiFormat('Can\'t use the file for results. The file already exists.', BaseConsole::FG_PURPLE);
                echo $error; die();
            } else {
                $data = 'The list of websites with Yii framework:'.PHP_EOL;
                file_put_contents($resfile, $data);
            }
        }

        $url = $this->ansiFormat($file, BaseConsole::FG_CYAN);
        echo 'Checking list of websites From the file '.$url.PHP_EOL;
        $fp = @fopen($file, "r"); // Открываем файл в режиме чтения
        if ($fp) {
            while (!feof($fp)) {
                $website = fgets($fp, 999);
                if (!empty($website)) {
                    if ($output) {
                        $url = $this->ansiFormat('This website from the file has been taken: '.$website, BaseConsole::FG_YELLOW);
                        echo PHP_EOL.$url.PHP_EOL;
                    }
                    $this->actionUrl(trim($website), $resfile, $output);
                    if ($output) { 
                        echo PHP_EOL; 
                    }
                }
            }
            fclose($fp);
        } else {
            $error = $this->ansiFormat('Can\'t open the file', BaseConsole::FG_PURPLE);
            echo $error;
        }
        
        return ExitCode::OK;
    }

    protected function check($content) {
        //by CSRF Token:
        if (preg_match('@meta name\=\"csrf\-token\"@', $content)) {
            return true;
        }

        //by JS file names:
        if (preg_match('@yii\.js|yii\.validation\.js|yii\.activeForm\.js@', $content)) {
            return true;
        }

        return false;
    }

    // Help methods for different url types:
    protected function clearwww($website) {
        if (preg_match('@www\.@', $website)) {
            $website = str_replace('www.', '', $website);
        }
        return $website;
    }

    protected function clearhttps($website) {
        if (preg_match('@^http\:\/\/|https\:\/\/@', $website)) {
            $website = str_replace('https://', '', $website);
            $website = str_replace('http://', '', $website);
        }
        return $website;
    }

    protected function setwww($website) {
        if (!preg_match('@www\.@', $website)) {
            $website = 'www.'.$website;
        }
        return $website;
    }

    protected function sethttp($website) {
        if (!preg_match('@^http\:\/\/|https\:\/\/@', $website)) {
            $website = 'http://'.$website;
        }
        return $website;
    }

    protected function sethttps($website) {
        if (!preg_match('@^https\:\/\/@', $website)) {
            $website = str_replace('http://', '', $website);
            $website = 'https://'.$website;
        }
        return $website;
    }
}
