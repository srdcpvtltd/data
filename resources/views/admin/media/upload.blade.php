<script type="text/x-handlebars-template" id="filePreviewTemplate">
    <div class="row">

        <div class="col-md-12">
            <div class="file-row">
                <div class="dz-actions pull-right">
                    <a data-dz-remove class="btn btn-sm btn-warning cancel">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                        <span>Cancel</span>
                    </a>
                    <a data-dz-remove class="btn btn-sm btn-danger delete">
                        <i class="glyphicon glyphicon-trash"></i>
                        <span>Delete</span>
                    </a>
                </div>
                <div class="dz-preview-file">
                    <span class="preview"><img data-dz-thumbnail/></span>
                </div>
                <div class="dz-file-info">
                    <p class="name mb-0" data-dz-name></p>
                    <p class="size mb-0" data-dz-size></p>
                    <strong class="error text-danger" data-dz-errormessage></strong>
                </div>
                <div class="clearfix"></div>

                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                     aria-valuenow="0">
                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                </div>
            </div>
        </div>
    </div>
</script>
<div class="previews-ctn"></div>
