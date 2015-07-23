<link rel="stylesheet" href="<?=P_SYSPATH?>static/js/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />
<!-- production -->
<script type="text/javascript" src="<?=P_SYSPATH?>static/js/plupload/js/plupload.full.min.js"></script>
<script type="text/javascript" src="<?=P_SYSPATH?>static/js/plupload/js/jquery.ui.plupload/jquery.ui.plupload.js"></script>
<script type="text/javascript" src="<?=P_SYSPATH?>static/js/plupload/js/i18n/zh_CN.js"></script>


<p class="tjtip">上传文件区</p>

<form id="form" method="post" action="../dump.php" >
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
            url : '<?=P_SYSPATH?>index.php?m=api&a=uploadFile',

            // User can upload no more then 20 files in one go (sets multiple_queues to false)
            max_file_count: 20,

            chunk_size: '1mb',

            // Resize images on clientside if we can
            resize : {
                width : 200,
                height : 200,
                quality : 90,
                crop: true // crop to exact dimensions
            },

            filters : {
                // Maximum file size
                max_file_size : '1000mb',
                // Specify what files to browse for
                mime_types: [
                    {title : "Image files", extensions : "jpg,gif,png"},
                    {title : "Zip files", extensions : "zip,rar"}
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
                thumbs: true, // Show thumbs
                active: 'thumbs'
            },

            // Flash settings
            flash_swf_url : '<?=P_SYSPATH?>static/js/plupload/js/Moxie.swf',

            // Silverlight settings
            silverlight_xap_url : '<?=P_SYSPATH?>static/js/plupload/js/Moxie.xap'
        });


        // Handle the case when form was submitted before uploading has finished
        $('#form').submit(function(e) {
            // Files in queue upload them first
            if ($('#uploader').plupload('getFiles').length > 0) {

                // When all files are uploaded submit form
                $('#uploader').on('complete', function() {
                    $('#form')[0].submit();
                });

                $('#uploader').plupload('start');
            } else {
                alert("You must have at least one file in the queue.");
            }
            return false; // Keep the form from submitting
        });
    });
</script>

<p class="tjtip">已上传文件</p>
<table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style">
    <tbody>
        <tr>
            <th width="60%">文件名</th>
            <th width="10%">上传日期</th>
            <th>操作</th>
        </tr>
        <tr>
            <td class="left">文件名1</td>
            <td>2015-1-1</td>
            <td>
                <input type="button" value="删除" class="btn67 delFileBtn">
                <input type="button" value="下载" class="btn67 downloadFileBtn">
            </td>
        </tr>
        <tr>
            <td class="left">文件名1</td>
            <td>2015-1-1</td>
            <td>
                <input type="button" value="删除" class="btn67 delFileBtn">
                <input type="button" value="下载" class="btn67 downloadFileBtn">
            </td>
        </tr>
    </tbody>

</table>

