/**
 * Created by charming on 16/4/22.
 */
$(document).ready(function () {
    initQuestionActions();
});

function initQuestionActions() {
    $('.question-follow-action').click(function () {
        questionId = $(this).attr('data-id');
        $questionFollowCount = $(this).siblings('.question-follow-count');
        if(followQuestion(questionId)) {
            $(this).addClass('text-muted');
            $(this).text('已关注');
            $questionFollowCount.text(parseInt($questionFollowCount.text()) + 1);
        }
    });
}

function followQuestion(id) {
    return doAction('follow', 'question', id);
}