$(document).ready(function () {
    $(document).on('click', '.reply', function (e) {
        e.preventDefault();
        const postId = $(this).data('post-id');
        const postContainer = $(this).closest('.append-reply');

        const replyContainer = $('<div class="reply-container"></div>');

        const existingReply = postContainer.find('.reply-container');

        if (existingReply.length) {
            existingReply.remove();
            return;
        }

        $('.reply-container').not(postContainer.find('.reply-container')).remove();

        const replyForm = `
            <div class="send-reply">
                <form name="reply_form" method="POST" action="process/reply_proc.php">
                    <input type="hidden" name="post_id" value="${postId}">
                    <input type="text" name="reply_text" class="input-reply" placeholder="Send reply" required>
                    <input type="submit" value="Reply" class="btn-reply">
                </form>
            </div>`;

        fetch('includes/fetch_replies.php?post_id=' + postId)
            .then(response => response.json())
            .then(data => {
                if (data.replyInfo.length > 0) {
                    data.replyInfo.forEach(reply => {
                        let likeImg = (reply.liked) ? 'liked.png' : 'like.png';
                        const eachReply = `
                            <div class="each-reply">
                                <hr>
                                <div class="content-padding">
                                    <div class="flex-row">
                                        <img src="images/profilepics/${reply.profilePic}" class="profile-pic">
                                        <div>
                                            <span class="bold">${reply.displayName}</span>
                                            <a href="userpage.php?user_id=${reply.userId}" class="link-user">@${reply.username}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="reply-text">
                                    ${reply.postText}
                                </div>
                                <div class="content-padding">
                                    <div class="space-between">
                                        <div class="timestamp">${reply.date}</div>
                                        <form name="like_form" method="POST" action="process/like_proc.php">
                                            <input type="hidden" name="post_id" value="${reply.postId}">
                                            <input type="image" src="images/icons/${likeImg}" class="icon-size">
                                        </form>
                                    </div>
                                </div>
                            </div>`;
                        replyContainer.append(eachReply);
                    });
                }
                replyContainer.append(replyForm);
                postContainer.append(replyContainer);
                replyContainer.find('input.reply-input').focus();
            });
    });
    
    $(document).on('submit', 'form[name="reply_form"]', function (e) {
        e.preventDefault();
        const form = $(this);
        const formData = form.serialize();
        const postId = form.find('input[name="post_id"]').val();

        $.post('process/reply_proc.php', formData, function () {
            form.closest('.reply-container').remove();
            $('.reply[data-post-id="' + postId + '"]').trigger('click');
        });
    });

    $(document).on('submit', 'form[name="like_form"]', function (e) {
        e.preventDefault();
        const form = $(this);
        const postId = form.find('input[name="post_id"]').val();
        const likeImg = form.find('input[type="image"]');

        $.post('process/like_proc.php', { post_id: postId }, function (response) {
            if (response.success) {
                const newSrc = response.liked ? 'images/icons/liked.png' : 'images/icons/like.png';
                likeImg.attr('src', newSrc);
            } else {
                console.error('Like failed:', response.message);
            }
        });
    });

});