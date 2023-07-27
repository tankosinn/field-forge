(function ($) {
    "use strict";

    $(function () {
        $.each($(".gallery"), function (idx, element) {
            var galleryDropzone = new Dropzone(
                $(element).find(".add-image")[0],
                {
                    uploadMultiple: true,
                    url: " ",
                    createImageThumbnails: false,
                    previewsContainer: false,
                    autoProcessQueue: false,
                    acceptedFiles: "image/*",
                }
            );

            galleryDropzone.on("addedfile", function (file) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        RequestHandler.Request(
                            "ziyaret/fotograf",
                            null,
                            {
                                image: file,
                                type: $(element).data("type"),
                                visit: $(element).data("visit"),
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude,
                            },
                            function (response) {
                                if (response.status) {
                                    toastr.success("Fotoğraf eklendi.");
                                    var image = $(
                                        '<div class="image"><img src="' +
                                            window.location.protocol +
                                            "//" +
                                            window.location.host +
                                            "/storage/visit/" +
                                            response.file_name +
                                            '"></div>'
                                    ).clone();

                                    $(element).append(image);
                                }
                            },
                            function (error) {
                                RequestHandler.ErrorHandler(
                                    error.responseJSON.errors
                                );
                            }
                        );
                    },
                    function () {
                        toastr.warning(
                            "Konumunuzunu açmadan ziyarete başlayamazsınız."
                        );
                    },
                    {
                        enableHighAccuracy: true,
                    }
                );
                if (!Dropzone.isValidFile(file, this.options.acceptedFiles)) {
                    toastr.warning(
                        "Yüklenen dosya geçerli bir formatta değildir."
                    );
                }
            });
        });
    });
})(jQuery);
