toastr.options.progressBar = true;
toastr.options.timeOut = 4000;
toastr.options.positionClass = "toast-top-center";
toastr.options.preventDuplicates = true;

(function ($) {
    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || "");
            } else {
                o[this.name] = this.value || "";
            }
        });
        return o;
    };

    $(function () {
        $.addRow = function (
            copy,
            paste,
            prepend = false,
            callback,
            prevCallback = false
        ) {
            var element = $(copy).clone();
            element.removeAttr("id");

            if (typeof callback === "function" && !prevCallback) {
                callback(element, paste);
            }

            if (prepend) {
                $(paste).prepend(element);
            } else {
                $(paste).append(element);
            }

            if (typeof callback === "function" && prevCallback) {
                callback(element, paste);
            }
        };

        $.deleteRow = function (
            Obj,
            paste,
            callback,
            removable = false,
            params,
            type = "table"
        ) {
            var element = null;

            if (type === "table") {
                element = $(Obj).closest("td").closest("tr");
            } else if (type === "list") {
                element = $(Obj).closest("li");
            }

            if (removable || $(paste).find("tr:visible").length !== 1) {
                $(Obj).hide();
                $(element).remove();
            } else {
                console.log(element);
                $(element).find("input").val("").change();
                $(element).find("textarea").val("").change();
            }

            if (typeof callback === "function") {
                callback(params);
            }
        };

        $(".select2").select2({
            language: "tr",
            width: "100%",
        });

        // Internet Connection
        var connectionModal = $.confirm({
            lazyOpen: true,
            icon: "fas fa-wifi",
            title: "İnternet Yok",
            content:
                "İnternet bağlantısı tekrar sağlanana kadar lütfen bekleyin, bağlantı sağlandıktan sonra kaldığınız yerden devam edebilirsiniz.",
            theme: "supervan",
            type: "orange",
            typeAnimated: true,
            backgroundDismiss: function () {
                return false; // modal wont close.
            },
            buttons: false,
            closeIcon: false,
            autoClose: false,
        });

        var setConnection = function (connection) {
            if (connection) {
                if (connectionModal.isOpen()) {
                    connectionModal.close();
                }
            } else {
                connectionModal.open();
            }
        };

        setConnection(window.navigator.onLine);

        window.addEventListener("offline", () => {
            setConnection(false);
        });

        window.addEventListener("online", () => {
            setConnection(true);
        });
    });
})(jQuery);
