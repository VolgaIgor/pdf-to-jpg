<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

use DirectoryIterator;

class ClearController extends Controller
{
    const MAX_LIVE_ZIP_MIN = 10;

    public function actionAll()
    {
        $uploadsDir = Yii::getAlias("@app/web/uploads");
        $runtimeDir = Yii::getAlias("@runtime/pdf");

        foreach (new DirectoryIterator($uploadsDir) as $fileinfo) {
            if (!$fileinfo->isFile() || $fileinfo->getExtension() !== 'zip') continue;
            unlink($fileinfo->getRealPath());
        }
        exec("rm -R {$runtimeDir}");

        return ExitCode::OK;
    }

    public function actionOld()
    {
        $uploadsDir = Yii::getAlias("@app/web/uploads");

        foreach (new DirectoryIterator($uploadsDir) as $fileinfo) {
            if (!$fileinfo->isFile() || $fileinfo->getExtension() !== 'zip') continue;

            $createTime = $fileinfo->getCTime();
            if ($createTime + static::MAX_LIVE_ZIP_MIN * 60 < time()) {
                unlink($fileinfo->getRealPath());
            }
        }

        return ExitCode::OK;
    }
}
