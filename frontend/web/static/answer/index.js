/**
 * Created by charming on 16/4/26.
 */
$(document).ready(function () {
    initAnswerActions();
});

function initAnswerActions() {
    $('.answer-like-action').click(function () {
        var answerId = $(this).attr('data-id');
        var $answerLikeCount = $(this).children('.answer-like-count');
        if(likeAnswer(answerId)) {
            $answerLikeCount.text(parseInt($answerLikeCount.text()) + 1);
            $(this).addClass('done');
        }
    });

    $('.answer-favorite-action').click(function () {
        var answerId = $(this).attr('data-id');
        if(favoriteAnswer(answerId)) {
            $(this).addClass('done');
        }
    });
}

function favoriteAnswer(id) {
    return doAction('favorite', 'answer', id);
}

function likeAnswer(id) {
    return doAction('like', 'answer', id);
}




