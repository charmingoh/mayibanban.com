/**
 * Created by charming on 16/4/24.
 */
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

function doAction(action, type, id) {
    var isSuccess = false;
    $.ajax({
        url: '/user/do',
        type: 'get',
        async: false,
        data: {
            action: action,
            type: type,
            id: id
        },
        dataType: 'json',
        success: function (result) {
            if (result.isSuccess) {
                isSuccess = result.isSuccess;
            } else {
                notify.error(result.msg);
            }
        }
    });
    return isSuccess;
}

var notify = function () {
    var delay = 2;

    function success(message) {
        notie.alert(1, message, delay);
    }

    function warning(message) {
        notie.alert(2, message, delay);
    }

    function error(message) {
        notie.alert(3, message, delay);
    }

    function info(message) {
        notie.alert(4, message, delay);
    }

    return {
        success: success,
        warning: warning,
        error: error,
        info: info
    };
}();