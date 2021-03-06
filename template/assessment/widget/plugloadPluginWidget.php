<link rel="stylesheet" href="<?=P_SYSPATH?>static/js/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />
<!-- production -->
<script type="text/javascript" src="<?=P_SYSPATH?>static/js/plupload/js/plupload.full.min.js"></script>
<script type="text/javascript" src="<?=P_SYSPATH?>static/js/plupload/js/jquery.ui.plupload/jquery.ui.plupload.js"></script>
<script type="text/javascript" src="<?=P_SYSPATH?>static/js/plupload/js/i18n/zh_CN.js"></script>
<style>
    .plupload_wrapper table td{line-height:5px;}
</style>

<p class="tjtip">上传文件区</p>

<form id="form" method="post" action="" >
    <div id="uploader">
    </div>
    <br />
</form>

<script type="text/javascript">
    // Initialize the widget when the DOM is ready
    $(function() {
        $("#uploader").plupload({
            // General settings
            runtimes : 'html5,flash,silverlight,html4',
            url : '<?=P_SYSPATH?>index.php?m=unlogin&a=assessUnlogin&act=uploadFile',

            // User can upload no more then 20 files in one go (sets multiple_queues to false)
            max_file_count: 20,

            chunk_size: '10mb',

            // Resize images on clientside if we can
/*            resize : {
                width : 200,
                height : 200,
                quality : 90,
                crop: true // crop to exact dimensions
            },*/

            filters : {
                // Maximum file size
                max_file_size : '10mb',
                // Specify what files to browse for
                mime_types: [
                    {title : "Image files", extensions : "jpg,gif,png"},
                    {title : "Zip files", extensions : "zip,rar"},
                    {title : "Doc files", extensions : "doc,psw,pwd,xls,csv,xlt,vsd,ppt,pdf,docx"}
                ]
            },

            // Rename files by clicking on their titles
            rename: true,

            // Sort files
            sortable: true,

            // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
            dragdrop: true,

            // Views to activate
            views: {
                list: true,
                active: 'thumbs'
            },

            // Flash settings
            flash_swf_url : '<?=P_SYSPATH?>static/js/plupload/js/Moxie.swf',

            // Silverlight settings
            silverlight_xap_url : '<?=P_SYSPATH?>static/js/plupload/js/Moxie.xap',
            init: {
                FileUploaded: function(up, file, info) {
                    // 上传回调
                    var dataObj=eval("("+info.response+")");
                    if(dataObj.error==0){
                        Assess.prototype.pushPlugFile({url:dataObj.url,cName:file.name});
                    }
                }
            }
        });

    });
    function downFile(fileId){
        var url = '';
        window.open(url);
    }
</script>

<!--下载区-->
<?=$widget->getDownloadArea($plupFileList);?>


