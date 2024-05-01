<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class UploadController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        $this->response->format = Response::FORMAT_JSON;
        $this->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $response = $event->sender;
            if (!$response->isSuccessful) {
                $response->data = [
                    'success' => false,
                    'message' => $response->data['message'],
                ];
            }
        });
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        if (!($file = UploadedFile::getInstanceByName('file'))) {
            return [
                'success' => false,
                'message' => 'Файл не загружен',
            ];
        }

        if ($file->extension !== 'pdf') {
            return [
                'success' => false,
                'message' => 'Недопустимый файл',
            ];
        }

        $tmpFilename = Yii::$app->getSecurity()->generateRandomString();
        $tmpDirpath = Yii::getAlias("@runtime/pdf/{$tmpFilename}");

        $tmpFilepath = "{$tmpDirpath}.pdf";
        $file->saveAs($tmpFilepath);

        mkdir($tmpDirpath, 0777, true);

        $outputFilename = $tmpDirpath . '/' . 'page_%03d.jpg';

        $resultConvert = exec("gs -sOutputFile={$outputFilename} -dBATCH -dNOPAUSE -dSAFER -sDEVICE=jpeg -r300 -dJPEGQ=100 {$tmpFilepath}");
        unlink($tmpFilepath);

        if ($resultConvert === false) {
            exec("rm -R {$tmpDirpath}");
            return [
                'success' => false,
                'link' => 'Не удалось сконвертировать файл',
            ];
        }

        $archiveFilepath = "{$tmpDirpath}.zip";
        $resultZip = exec("zip -j {$archiveFilepath} {$tmpDirpath}/*");
        if ($resultZip === false) {
            exec("rm -R {$tmpDirpath}");
            return [
                'success' => false,
                'link' => 'Не удалось создать архив',
            ];
        }

        $targetFilepath = Yii::getAlias("@webroot/uploads/{$tmpFilename}.zip");

        exec("rm -R {$tmpDirpath}");
        exec("mv {$archiveFilepath} {$targetFilepath}");

        return [
            'success' => true,
            'link' => Yii::getAlias("@web/uploads/{$tmpFilename}.zip"),
        ];
    }
}
