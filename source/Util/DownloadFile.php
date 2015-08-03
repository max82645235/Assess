<?
//====================================================
// FileName:download.class.php
// Summary: �ļ�������
// 
// ʹ�÷�����
// $download = new download('php,exe,html',false);
// if(!$download->downloadfile($filename))
// {
//	  echo $download->geterrormsg();
// }      
//====================================================
class DownloadFile
{
    var $debug=true;
    var $errormsg='';
    var $Filter=array();
    var $filename='';
    var $mineType='';
    var $xlq_filetype=array();

    function __construct($fileFilter='',$isdebug=true)
    {
        $this->setFilter($fileFilter);
        $this->setdebug($isdebug);
        $this->setfiletype();
    }

    function setFilter($fileFilter)
    {
        if(empty($fileFilter)) return ;
        $this->Filter=explode(',',strtolower($fileFilter));
    }
    function setdebug($debug)
    {
        $this->debug=$debug;
    }

    function setfilename($filename)
    {
        $this->filename=$filename;
    }

    function downloadfile($filename)
    {
        $this->setfilename($filename);
        if($this->filecheck())
        {
            $fileInfo  = get_headers($filename,true);
            $fn = array_pop( explode( '/', strtr( $this->filename, '\\', '/' ) ) );
            $chunk = 10 * 1024 * 1024;
            ob_get_flush();
            header( "Pragma: public" );
            header( "Expires: 0" ); // set expiration time
            header( "Cache-Component: must-revalidate, post-check=0, pre-check=0" );
            header( "Content-type:".$this->mineType );
            header( "Content-Length: ".$fileInfo['Content-Length'] ) ;
            header( "Content-Disposition: attachment; filename=\"$fn\"" );
            header( 'Content-Transfer-Encoding: binary' );
            $handle=fopen($filename,"r");//ʹ�ô�ģʽΪr
            // Repeat reading until EOF
            while (!feof($handle)) {
                echo fread($handle,$chunk );
                ob_flush();  // flush output
                flush();
            }
                return true;
        }
        else
        {
            return false;
        }
    }
    function geterrormsg()
    {
        return $this->errormsg;
    }


    public function  checkRemoteFileExists($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $result = curl_exec($curl);
        $found = false;
        if ($result !== false)
        {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200)
            {
                $found = true;
            }
        }
        curl_close($curl);
        return $found;
    }


    function filecheck()
    {
        $filename=$this->filename;
        if($this->checkRemoteFileExists($filename))
        {
            $filetype=strtolower(array_pop(explode('.',$filename)));
            if(in_array($filetype,$this->Filter))
            {
                $this->errormsg.=$filename.'���������أ�';
                if($this->debug) exit($filename.'���������أ�') ;
                return false;
            }else
            {
                if ( function_exists( "mime_content_type" ) )
                {
                    $this->mineType = mime_content_type( $filename );
                }
                if(empty($this->mineType))
                {
                    if( isset($this->xlq_filetype[$filetype]) )  $this->mineType = $this->xlq_filetype[$filetype];
                }
                if(!empty($this->mineType))
                    return true;
                else
                {
                    $this->errormsg.='��ȡ'.$filename.'�ļ�����ʱ�������󣬻��߲�����Ԥ���ļ�������';
                    if($this->debug) exit('��ȡ�ļ����ͳ���');
                    return false;
                }
            }
        }else
        {
            $this->errormsg.=$filename.'������!';
            if($this->debug) exit($filename.'������!') ;
            return false;
        }
    }

    function setfiletype()
    {
        $this->xlq_filetype['chm']='application/octet-stream';
        $this->xlq_filetype['ppt']='application/vnd.ms-powerpoint';
        $this->xlq_filetype['xls']='application/vnd.ms-excel';
        $this->xlq_filetype['doc']='application/msword';
        $this->xlq_filetype['exe']='application/octet-stream';
        $this->xlq_filetype['rar']='application/octet-stream';
        $this->xlq_filetype['js']="javascript/js";
        $this->xlq_filetype['css']="text/css";
        $this->xlq_filetype['hqx']="application/mac-binhex40";
        $this->xlq_filetype['bin']="application/octet-stream";
        $this->xlq_filetype['oda']="application/oda";
        $this->xlq_filetype['pdf']="application/pdf";
        $this->xlq_filetype['ai']="application/postsrcipt";
        $this->xlq_filetype['eps']="application/postsrcipt";
        $this->xlq_filetype['es']="application/postsrcipt";
        $this->xlq_filetype['rtf']="application/rtf";
        $this->xlq_filetype['mif']="application/x-mif";
        $this->xlq_filetype['csh']="application/x-csh";
        $this->xlq_filetype['dvi']="application/x-dvi";
        $this->xlq_filetype['hdf']="application/x-hdf";
        $this->xlq_filetype['nc']="application/x-netcdf";
        $this->xlq_filetype['cdf']="application/x-netcdf";
        $this->xlq_filetype['latex']="application/x-latex";
        $this->xlq_filetype['ts']="application/x-troll-ts";
        $this->xlq_filetype['src']="application/x-wais-source";
        $this->xlq_filetype['zip']="application/zip";
        $this->xlq_filetype['bcpio']="application/x-bcpio";
        $this->xlq_filetype['cpio']="application/x-cpio";
        $this->xlq_filetype['gtar']="application/x-gtar";
        $this->xlq_filetype['shar']="application/x-shar";
        $this->xlq_filetype['sv4cpio']="application/x-sv4cpio";
        $this->xlq_filetype['sv4crc']="application/x-sv4crc";
        $this->xlq_filetype['tar']="application/x-tar";
        $this->xlq_filetype['ustar']="application/x-ustar";
        $this->xlq_filetype['man']="application/x-troff-man";
        $this->xlq_filetype['sh']="application/x-sh";
        $this->xlq_filetype['tcl']="application/x-tcl";
        $this->xlq_filetype['tex']="application/x-tex";
        $this->xlq_filetype['texi']="application/x-texinfo";
        $this->xlq_filetype['texinfo']="application/x-texinfo";
        $this->xlq_filetype['t']="application/x-troff";
        $this->xlq_filetype['tr']="application/x-troff";
        $this->xlq_filetype['roff']="application/x-troff";
        $this->xlq_filetype['shar']="application/x-shar";
        $this->xlq_filetype['me']="application/x-troll-me";
        $this->xlq_filetype['ts']="application/x-troll-ts";
        $this->xlq_filetype['gif']="image/gif";
        $this->xlq_filetype['jpeg']="image/pjpeg";
        $this->xlq_filetype['jpg']="image/pjpeg";
        $this->xlq_filetype['jpe']="image/pjpeg";
        $this->xlq_filetype['ras']="image/x-cmu-raster";
        $this->xlq_filetype['pbm']="image/x-portable-bitmap";
        $this->xlq_filetype['ppm']="image/x-portable-pixmap";
        $this->xlq_filetype['xbm']="image/x-xbitmap";
        $this->xlq_filetype['xwd']="image/x-xwindowdump";
        $this->xlq_filetype['ief']="image/ief";
        $this->xlq_filetype['tif']="image/tiff";
        $this->xlq_filetype['tiff']="image/tiff";
        $this->xlq_filetype['pnm']="image/x-portable-anymap";
        $this->xlq_filetype['pgm']="image/x-portable-graymap";
        $this->xlq_filetype['rgb']="image/x-rgb";
        $this->xlq_filetype['xpm']="image/x-xpixmap";
        $this->xlq_filetype['txt']="text/plain";
        $this->xlq_filetype['c']="text/plain";
        $this->xlq_filetype['cc']="text/plain";
        $this->xlq_filetype['h']="text/plain";
        $this->xlq_filetype['html']="text/html";
        $this->xlq_filetype['htm']="text/html";
        $this->xlq_filetype['htl']="text/html";
        $this->xlq_filetype['rtx']="text/richtext";
        $this->xlq_filetype['etx']="text/x-setext";
        $this->xlq_filetype['tsv']="text/tab-separated-values";
        $this->xlq_filetype['mpeg']="video/mpeg";
        $this->xlq_filetype['mpg']="video/mpeg";
        $this->xlq_filetype['mpe']="video/mpeg";
        $this->xlq_filetype['avi']="video/x-msvideo";
        $this->xlq_filetype['qt']="video/quicktime";
        $this->xlq_filetype['mov']="video/quicktime";
        $this->xlq_filetype['moov']="video/quicktime";
        $this->xlq_filetype['movie']="video/x-sgi-movie";
        $this->xlq_filetype['au']="audio/basic";
        $this->xlq_filetype['snd']="audio/basic";
        $this->xlq_filetype['wav']="audio/x-wav";
        $this->xlq_filetype['aif']="audio/x-aiff";
        $this->xlq_filetype['aiff']="audio/x-aiff";
        $this->xlq_filetype['aifc']="audio/x-aiff";
        $this->xlq_filetype['swf']="application/x-shockwave-flash";
    }
}