$(document).ready(function () {
    $(document).on('click', '.reply', function (e) {
        e.preventDefault();
        const tweetId = $(this).data('tweet-id');
        const tweetContainer = $(this).closest('.tweet_container');

        const existingReply = tweetContainer.find('.reply_container');

        if (existingReply.length) {
            existingReply.remove();
            return;
        }

        $('.reply_container').not(tweetContainer.find('.reply_container')).remove();

        const replyContainer = $('<div class="reply_container"></div>');

        const replyForm = `
            <div class="send_reply">
                <form name="reply_form" action="process/reply_proc.php" method="post">
                    <input type="hidden" name="tweet_id" value="${tweetId}">
                    <textarea name="reply_text" cols="65" rows="2"></textarea>
                    <input type="submit" value="Reply" class="reply-button">
                </form>
                <hr>
            </div>`;

        fetch('includes/fetch_replies.php?tweet_id=' + tweetId)
            .then(response => response.json())
            .then(data => {
                if (data.replyInfo.length > 0) {
                    data.replyInfo.forEach(reply => {
                        const eachReply = `
                            <div class="display_reply">
                                <div class="flex-row">
                                    <img class="bannericons" src="images/profilepics/${reply.profilePic}">
                                    <div>
                                        <div class="flex-column">
                                            <span class="bold">${reply.firstName} ${reply.lastName} 
                                                <a href="userpage.php?user_id=${reply.userId}"> @${reply.username}</a>
                                            </span>
                                            <div>
                                                <span class="bold">
                                                    <a href="userpage?user_id=${data.userId}">@${data.username}</a>
                                                </span> ${reply.tweetText}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-end">${reply.date}</div>
                                <hr>
                            </div>`;
                        replyContainer.append(eachReply);
                    });
                }
                replyContainer.append(replyForm);
                tweetContainer.append(replyContainer);
            });
    });

    $(document).on('submit', 'form[name="reply_form"]', function (e) {
        e.preventDefault();
        const form = $(this);
        const formData = form.serialize();
        const tweetId = form.find('input[name="tweet_id"]').val();

        $.post('process/reply_proc.php', formData, function () {
            form.closest('.reply_container').remove();
            $('.reply[data-tweet-id="' + tweetId + '"]').trigger('click');
        });
    });
});