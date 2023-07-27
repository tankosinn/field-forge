var RequestHandler = {
    Parameters: [],
    AjaxOptions: {
        type: "POST",
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        contentType: false,
        processData: false,
    },
    Prefix: "",
    ActiveRoute: "",
    AjaxSetup: function (options) {
        $.ajaxSetup(Object.assign({}, this.AjaxOptions, options));
    },
    /**
     * @return {string}
     */
    Controller: function (path) {
        return (
            window.location.protocol +
            "//" +
            window.location.host +
            (this.Prefix !== "" ? "/" + this.Prefix : "") +
            (typeof path !== "undefined" && path.length > 0 ? "/" + path : "")
        );
    },
    GetParams: function (url) {
        var query = url.split("?");
        var query_string = {};
        if (query.length > 1 && query[1].length > 0) {
            var vars = query[1].split("&");
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split("=");
                var key =
                    typeof pair[0] === "undefined"
                        ? ""
                        : decodeURIComponent(pair[0]);
                var value =
                    typeof pair[1] === "undefined"
                        ? ""
                        : decodeURIComponent(pair[1]);
                // If first entry with this name
                if (typeof query_string[key] === "undefined") {
                    query_string[key] = decodeURIComponent(value);
                    // If second entry with this name
                } else if (typeof query_string[key] === "string") {
                    query_string[key] = [
                        query_string[key],
                        decodeURIComponent(value),
                    ];
                    // If third or later entry with this name
                } else {
                    query_string[key].push(decodeURIComponent(value));
                }
            }
        }
        return query_string;
    },
    /**
     * @return {boolean||string||array}
     */
    ParameterHandler: function (parameters, index, formData) {
        if (Array.isArray(parameters) || jQuery.isPlainObject(parameters)) {
            var params = [];
            for (var key in parameters) {
                var idx =
                    index.length > 0 && index !== 0
                        ? index + "[" + key + "]"
                        : key;
                var value = parameters[key];

                if (
                    Array.isArray(parameters[key]) ||
                    jQuery.isPlainObject(parameters[key])
                ) {
                    var recursive = RequestHandler.ParameterHandler(
                        value,
                        idx,
                        formData
                    );
                    if (typeof formData !== "undefined" && formData !== false) {
                        params.push(recursive);
                    }
                } else {
                    if (
                        typeof formData !== "undefined" &&
                        formData !== false &&
                        formData !== null
                    ) {
                        formData.append(idx, value);
                    } else {
                        params.push(idx + "=" + value);
                    }
                }
            }

            if (!formData) {
                return params.join("&");
            }
        } else {
            if (
                typeof formData !== "undefined" &&
                formData !== false &&
                formData !== null
            ) {
                formData.append(index, parameters);
            } else {
                return index + "=" + parameters;
            }
        }
    },
    /**
     *
     * @param {string} path
     * @param {string,boolean} form
     * @param parameters
     * @param {boolean}info
     * @param {function|boolean}fn
     * @param ajax_setup
     * @constructor
     */
    Request: function (path, form, parameters, fn, errFn, ajax_setup) {
        if (!window.navigator.onLine) {
            toastr.danger("İnternet bağlantısı yok!");
            return;
        }

        this.AjaxSetup(ajax_setup);

        var formData = new FormData();

        if (typeof form !== "undefined" && form !== false) {
            $.each($(form), function (idx, elm) {
                var formDataCache = new FormData(elm),
                    es,
                    e,
                    pair;
                for (
                    es = formDataCache.entries();
                    !(e = es.next()).done && (pair = e.value);

                ) {
                    formData.append(pair[0], pair[1]);
                }
            });
        }

        if (typeof parameters !== "undefined") {
            $.each(parameters, function (idx, elm) {
                RequestHandler.ParameterHandler(elm, idx, formData);
            });
        }

        $.ajax({
            url: this.Controller(path),
            data: formData,
            success: function (response) {
                if (typeof fn === "function") {
                    fn(response);
                }
            },
            error: function (xhr, status, error) {
                if (typeof errFn === "function") {
                    errFn(xhr, status, error);
                }
            },
        });
    },
    /**
     * @param query
     * @constructor
     */
    FilterHandler: function (query) {
        var params = [];
        $.each(query, function (idx, elm) {
            params.push(RequestHandler.ParameterHandler(elm, idx));
        });

        var url = window.location.href.split("?");

        window.location.href =
            url[0] + "?" + (params.length > 0 ? params.join("&") : "");
    },
    /**
     *
     * @param form
     * @param parameters
     * @param noAssign
     * @param validate
     * @param {function|boolean} validateErrorCallback
     * @constructor
     */
    Filter: function (form, parameters, noAssign) {
        var query = Object.assign(
            {},
            RequestHandler.GetParams(window.location.href),
            $(form).serializeObject(),
            typeof parameters !== "undefined" ? parameters : {}
        );

        if (typeof noAssign !== "undefined" && noAssign.length > 0) {
            for (var i in noAssign) {
                delete query[noAssign[i]];
            }
        }

        RequestHandler.FilterHandler(query);
    },
    ErrorHandler: function (errors) {
        toastr.clear();
        for (let key in errors) {
            toastr.warning(errors[key][0]);
        }
    },
};
