"use strict";

const chatApp = {
    data() {
        return {
            getMessagesUrl: getMessagesUrl,
            sendMessageUrl: sendMessageUrl,
            uploadFileUrl: uploadFileUrl,
            uploadAttachmentUrl: uploadAttachmentUrl,
            order_id: order_id,
            message: '',
            messages: [],
            isSendingMessage: false,
            attachments: [],
            receiver_id: receiver_id
        }
    },
    mounted() {
        this.getMessages();

        setTimeout(() => this.refresh(), 5000);

        this.initDropzone();
    },
    methods: {
        openChatBox() {
            $('.chatbox').toggleClass('chatbox--tray');

            $(".chatbox__body").scrollTop($(".chatbox__body")[0].scrollHeight);
        },
        getMessages() {
            axios.get(this.getMessagesUrl).then(response => {
                this.messages = response.data.messages;

                response.data.messages.forEach((item, index) => {
                    this.messages[index].created_at = moment(this.messages[index].created_at).format('MMM DD, YYYY | HH:mm');
                });
            })
        },
        sendMessage() {
            if (this.message.trim() == '') {
                return;
            }

            this.isSendingMessage = true;

            axios.post(this.sendMessageUrl, {
                content: this.message,
                attachments: this.attachments
            }).then(response => {
                this.messages = response.data.messages;
                this.isSendingMessage = false;
                this.message = '';
                $('.files-preview').empty();

                this.attachments = [];

                $(".chatbox__body").scrollTop($(".chatbox__body")[0].scrollHeight);
            }).catch(err => {
                this.isSendingMessage = false;
            })
        },
        refresh() {
            // make Ajax call here, inside the callback call:
            this.getMessages()
            setTimeout(() => {
                this.refresh()
            }, 5000);
            // ...
        },
        initDropzone() {
            let dropZone = new Dropzone("#attachFiles", {
                url: this.uploadFileUrl,
                paramName: "file",
                uploadMultiple: false,
                previewsContainer: ".files-preview",
                previewTemplate: $("#chat-upload-template").html(),
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            });

            dropZone.on('sending', (file, xhr, formData) => {
                this.isSendingMessage = true;
            });

            dropZone.on('success', (file, mediaId) => {
                if (isNaN(parseInt(mediaId))) {
                    return;
                }

                this.isSendingMessage = false;

                this.attachments.push(mediaId);
            });

            dropZone.on('error', (file, response) => {
                this.isSendingMessage = false;
            })

            dropZone.on('removedfile', (file) => {
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

                axios.delete(this.uploadAttachmentUrl + '?id=' + id).then(response => {
                    if (isNaN(parseInt(response))) {
                        return;
                    }

                    const index = array.indexOf(response);
                    if (index > -1) {
                        this.attachments.splice(index, 1);
                    }
                })
            });
        }
    }
}

Vue.createApp(chatApp).mount("#chatbox");
