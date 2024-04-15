
$(".navbar-toggler").click(() => {
    $(".navbar-collapse").toggleClass("show");
});

function noti(status, message, delay = 1500) {

    if (status == 'error') {
        var statuss = 'danger';
        var icon = '<i class="pe-1 fal fa-exclamation-circle"></i>';
    } else {
        var statuss = 'success';
        var icon = '<i class="pe-1 fal fa-check-circle"></i>';
    }

    var date = new Date().getTime();

    if (typeof $(".toast-container").html() === "undefined") {
        $("body").append(
            '<div class="toast-container mt-5 mt-sm-0 position-fixed toast-center p-3 top-0 end-0"></div>');
        $(".toast-container").append(
            `<div role="alert" aria-live="assertive" toasts-id="${date}" data-bs-delay="${delay}" aria-atomic="true" class="toast fade show"><div class="toast-header bg-${statuss}"><h5 class="me-auto m-0 fs-14">${icon} Thông báo</h5> <span data-bs-dismiss="toast" aria-label="Close" class="text-white cursor-pointer"><i class="fa fa-times fw-normal"></i></span></div><div class="toast-body"><span>${message}</span></div></div></div>`
        );
        var load = setInterval(() => {
            clearInterval(load);
            $(`[toasts-id="${date}"]`).remove();
        }, delay)

    } else {
        $(".toast-container").append(
            `<div role="alert" aria-live="assertive" toasts-id="${date}" data-bs-delay="${delay}" aria-atomic="true" class="toast fade show"><div class="toast-header bg-${statuss}"><h5 class="me-auto m-0 fs-14">${icon} Thông báo</h5> <span data-bs-dismiss="toast" aria-label="Close" class="text-white cursor-pointer"><i class="fa fa-times fw-normal"></i></span></div><div class="toast-body"><span>${message}</span></div></div></div>`
        );
        var load = setInterval(() => {
            clearInterval(load);
            $(`[toasts-id="${date}"]`).remove();
            if ($(".toast-container").html() == '') {
                $(".toast-container").remove();
            }
        }, delay)
    }
}
function loading() {
    $("body").append(`<div class="ta-loader">
    <div role="status" class="spinner-border text-main"
        style="width: 3.5rem; height: 3.5rem; animation-duration: 0.8s; border-width: 0.35em;">
        <span class="sr-only">Loading...</span>
    </div>
</div>`);
    var load = setInterval(() => {
        clearInterval(load);
        $(`.ta-loader`).remove();
    }, 700)
}
$(function () {
    loading();
})

$(window).on("resize", function () {
    scroll()
});
window.onscroll = function () {
    scroll()
};



function scroll() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        if ($(".ta-backto-top").length <= 0) {
            $("body").append(`<div class="ta-backto-top shadow-sm">
        <button class="btn btn-light btn-sm">
            <i class="fad fa-arrow-up px-1 py-1"></i>
        </button>
    </div>
    `);
        } else {
            $(".ta-backto-top").click(() => {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            })
        }
    } else {
        $(".ta-backto-top").remove();
    }
}


function CurlHttp(url, method = 'GET', data = null, button = null) {
    if (button == null) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            dataType: "json",
            statusCode: {
                403: function () {
                    noti("error", "Đường dẫn API không chính xác");
                },
                404: function () {
                    noti("error", "Đường dẫn API không chính xác");
                },
                500: function () {
                    noti("danger", "500 status code! server error");
                },
            },
            success: (data) => {
                if (!data && data == "undefined") {
                    noti("error", "Không thể lấy dữ liệu");
                } else {
                    if (data.href) {
                        setInterval(() => {
                            window.location.href = data.href;
                        }, 700);
                    }
                    noti(data.status, data.message);
                }
            },
        });
    } else {
        let textButton = button.html().trim();

        $.ajax({
            type: method,
            url: url,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            statusCode: {
                403: function () {
                    noti("error", "Đường dẫn API không chính xác");
                },
                404: function () {
                    noti("error", "Đường dẫn API không chính xác");
                },
                500: function () {
                    noti("danger", "500 status code! server error");
                },
                400: function (response) {
                    let data = JSON.parse(response.responseText);
                    if (!data && data == "undefined") {
                        noti("error", "Không thể lấy dữ liệu");
                    } else {
                        if (data.href) {
                            setInterval(() => {
                                window.location.href = data.href;
                            }, 700);
                        }
                        noti(data.status, data.message);
                    }
                },
                401: function (response) {
                    let data = JSON.parse(response.responseText);
                    if (!data && data == "undefined") {
                        noti("error", "Không thể lấy dữ liệu");
                    } else {
                        if (data.href) {
                            setInterval(() => {
                                window.location.href = data.href;
                            }, 700);
                        }
                        noti(data.status, data.message);
                    }
                }
            },
            beforeSend: function () {
                $("body").append(`<div class="ta-loader"><div role="status" class="spinner-border text-main" style="width: 3.5rem; height: 3.5rem; animation-duration: 0.8s; border-width: 0.35em;"><span class="sr-only">Loading...</span></div></div>`);

                button
                    .prop("disabled", !0)
                    .html('<i class="fas fa-spinner fa-spin"></i> Đang tải...');
            },
            complete: function () {
                button.prop("disabled", !1).html(textButton);
                $(`.ta-loader`).remove();
            },
            success: (data) => {
                if (!data && data == "undefined") {
                    noti("error", "Không thể lấy dữ liệu");
                } else {
                    if (data.href) {
                        setInterval(() => {
                            window.location.href = data.href;
                        }, 700);
                    }
                    noti(data.status, data.message);
                }
            },
        });
    }
}

function saochep(text) {
    return new Promise((resolve, reject) => {
        if (typeof navigator !== "undefined" && typeof navigator.clipboard !== "undefined" && navigator.permissions !== "undefined") {
            const type = "text/plain";
            const blob = new Blob([text], { type });
            const data = [new ClipboardItem({ [type]: blob })];
            navigator.permissions.query({name: "clipboard-write"}).then((permission) => {
                if (permission.state === "granted" || permission.state === "prompt") {
                    navigator.clipboard.write(data).then(resolve, reject).catch(reject);
                }
                else {
                    reject(new Error("Permission not granted!"));
                }
            });
        }
        else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
            var textarea = document.createElement("textarea");
            textarea.textContent = text;
            textarea.style.position = "fixed";
            textarea.style.width = '2em';
            textarea.style.height = '2em';
            textarea.style.padding = 0;
            textarea.style.border = 'none';
            textarea.style.outline = 'none';
            textarea.style.boxShadow = 'none';
            textarea.style.background = 'transparent';
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            try {
                document.execCommand("copy");
                document.body.removeChild(textarea);
                resolve();
            }
            catch (e) {
                document.body.removeChild(textarea);
                reject(e);
            }
        }
        else {
            reject(new Error("None of copying methods are supported by this browser!"));
        }
    });
    
}

async function copy(text) {
    await saochep(text);
    noti('success', `Đã sao chép <b>${text}</b>`)
}
$(function () {
    $('.nav-link[href="' + location.pathname + '"]').addClass('nuxt-link-exact-active nuxt-link-active').parent(1).addClass('active')
    $('.dropdown-item[href="' + location.pathname + '"]').addClass('nuxt-link-exact-active nuxt-link-active');
});

$("form[next]").submit(function (e) {
    e.preventDefault();
    CurlHttp($(this).attr("action"), $(this).attr("method"), new FormData(this), $(this).find('button[type=submit]'));
});

