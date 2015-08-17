<?php
/**
 * 将生成临时目录的文件夹做压缩并下载输出
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-13
* Time: 上午9:33
*/
require_once BATH_PATH."source/Util/ZipAssessFile/HZip.php";
require_once BATH_PATH."source/Util/DownloadFile.php";
class AssessZip{
    static function zipToLoad($tempDirPath){

        $zipFilePath = $tempDirPath."/".time().".zip";
        HZip::zipDir($tempDirPath, $zipFilePath);
        $download = new DownloadFile('php,exe,html',false);
        $download->setFileInLocal();
        $download->downloadfile($zipFilePath);
        //echo  $download->geterrormsg();
    }
}