$(document).ready(function () {
    $('#answerform-url').blur(function () {
        var url = $(this).val();
        getTitleByUrl(url);
    });
});

function getTitleByUrl(url) {
    if(url.length < 7) {
        return;
    }

    $.ajax({
        url: '/answer/title',
        type: 'post',
        async: true,
        data: {
            url: url
        },
        dataType: 'json',
        success: function (result) {
            if (result.isSuccess) {
                $('#answerform-title').val(result.data.title);
            }
        }
    });
}