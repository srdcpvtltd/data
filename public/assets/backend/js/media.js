"use strict";

Dropzone.autoDiscover = false;

function initializeDropzone(element) {
    if (element.dropzone) {
        return;
    }

    const config = JSON.parse(element.getAttribute('data-media-config'));
    const files = element.getAttribute('data-files') !== '' ? JSON.parse(element.getAttribute('data-files')) : '';
    const product_id = element.getAttribute('data-product_id');
    const disk = config.disk || 'public';
    const previewsContainer = config.previewsContainer;
    const previewTemplate = config.previewTemplate || '#filePreviewTemplate';
    const tag = config.tag || 'gallery';
    const single_upload = config.single_upload;


    const arrayBrackets = single_upload === undefined ? '[]' : '';
    const maxFiles = config.maxFiles === undefined ? null : config.maxFiles;

    const i = new Dropzone(`#${element.id}`, {
        init: function () {
            const self = this;

            if (files !== '') {

                $.each(files, function (k, img) {

                    const mockFile = img;
                    self.emit('addedfile', mockFile);
                    self.emit('complete', mockFile);
                    self.options.thumbnail.call(self, mockFile, img.image_url);
                    mockFile.previewElement.classList.add('dz-success');
                    mockFile.previewElement.classList.add('dz-complete');

                    if (!product_id) {
                        $('<input>', {
                            'id': 'media-' + img.id,
                            'type': 'hidden',
                            'name': config.key + arrayBrackets,
                            'value': img.id
                        }).appendTo(config.container);
                    }
                });
            }

            if (single_upload !== undefined) {
                this.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                });
            }

            this._updateMaxFilesReachedClass();
        },
        url: base_url + "/ch-admin/media?disk=" + disk,
        thumbnailWidth: 80,
        maxFiles: maxFiles,
        thumbnailHeight: 80,
        parallelUploads: 20,
        timeout: 0,
        maxFilesize: 20971520000,
        previewTemplate: $(previewTemplate).html(),
        previewsContainer: previewsContainer,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        sending: function (file, xhr, formData) {
            formData.append('_token', $('meta[name="_token"]').attr('content'));
            if (product_id) {
                formData.append('product_id', product_id);
                formData.append('tag', tag);
            }
        },
        success: function (file, response) {
            if (isNaN(parseInt(response))) {
                return;
            }


            if (!product_id) {
                $('<input>', {
                    'id': 'media-' + response,
                    'type': 'hidden',
                    'name': config.key + arrayBrackets,
                    'value': response
                }).appendTo(config.container);
            }

            const fileUploadedEvent = new CustomEvent('fileUploaded', {
                detail: {
                    product_id: response,
                    tag: tag,
                }
            });

            window.dispatchEvent(fileUploadedEvent);
        },
        removedfile: function (file) {

            $(file.previewElement).find('.delete').attr('disabled', 'disabled');

            let id;

            if ('xhr' in file) {
                id = file.xhr.response;
            }

            if ('id' in file) {
                id = file.id;
            }

            if (typeof id === 'undefined') {
                return;
            }

            $.ajax({
                url: base_url + "/ch-admin/media/destroy",
                data: {
                    _token: $('meta[name="_token"]').attr('content'),
                    id: id,
                    product_id: product_id
                },
                method: 'DELETE',
                success: function (response) {
                    if (isNaN(parseInt(response))) {
                        return;
                    }

                    $(file.previewElement).remove();

                    $(config.container + ' input#media-' + response).remove();
                },
                error: function() {
                    $(file.previewElement).find('.delete').removeAttr('disabled');
                }
            });
        },
        error: function (file, responseText) {
            $(file.previewElement).find('.error').text(responseText.errors);
        }
    });
}

const uploaderElements = document.querySelectorAll('.uploader .dropzone');
uploaderElements.forEach(function (element) {
    initializeDropzone(element);
});
