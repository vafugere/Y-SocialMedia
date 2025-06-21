<?php
class Search {
    public static function getSearchUsers($con, $search) {
        $stmt = $con->prepare('SELECT user_id, first_name, last_name, screen_name, profile_pic FROM users
            WHERE first_name LIKE ? OR last_name LIKE ? OR screen_name LIKE ? LIMIT 5');
        $searchTerm = "%{$search}%";
        $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $stmt->bind_result($user_id, $fname, $lname, $username, $profile_pic);
        $users = [];
        while ($stmt->fetch()) {
            $users[] = new User($user_id, $fname, $lname, $username, null, null, null, $profile_pic);
        }
        $stmt->close();
        return $users;
    }
    public static function getSearchTweets($con, $search) {
        $stmt = $con->prepare('SELECT tweet_id, tweet_text, user_id, date_created FROM tweets
            WHERE reply_to_tweet_id = 0
            AND tweet_text LIKE ?
            LIMIT 5');
        $searchTerm = "%{$search}%";
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $stmt->bind_result($tweet_id, $tweet_text, $user_id, $date_created);
        $newTweets = [];
        while ($stmt->fetch()) {
            $newTweets[] = [
                'tweet_id' => $tweet_id,
                'tweet_text' => $tweet_text,
                'user_id' => $user_id,
                'date_created' => $date_created
            ];
        }
        $stmt->close();

        $tweets = [];
        foreach ($newTweets as $row) {
            $user = User::getUserById($con, $row['user_id']);
            $tweets[] = [
                'tweet' => new Tweet($row['tweet_id'], $row['tweet_text'], $row['user_id'], null, null, $row['date_created']),
                'user' => $user
            ];
        }
        return $tweets;
    }
    public static function displaySearchUsers($con, $search) {
        $users = Search::getSearchUsers($con, $search);
        $userId = $_SESSION['userId'];
        if (!empty($users)) {
            echo    '<h3>Users</h3>';
            foreach ($users as $user) {
            $isFollowing = User::isFollowing($con, $userId, $user->userId);
            $followText = ($isFollowing) ? 'Unfollow' : 'Follow';
                echo    '
                        <div class="flex-row">
                            <img class="profile-icon" src="images/profilepics/' . $user->profilePic . '">
                            <div>
                                <a class="bold" href="userpage.php?user_id=' . $user->userId . '">@' . $user->username . '</a><br>' . $user->fullName() . '
                                <form action="process/follow_proc.php" method="post">
                                    <input type="hidden" name="user_id" value="' . $user->userId . '">
                                    <input type="submit" class="follow-button" value="' . $followText . '">
                                </form>
                            </div>
                        </div>
                        <hr>';
        
            }
        }
    }
    public static function displaySearchTweets($con, $search) {
        $tweets = Search::getSearchTweets($con, $search);
        $loggedUser = $_SESSION['userId'];
        if (!empty($tweets)) {
            echo    '<h3>Tweets</h3>';
            foreach($tweets as $item) {  
                $tweet = $item['tweet'];
                $user = $item['user'];
                $isLiked = Tweet::isLiked($con, $tweet->tweetId, $loggedUser);
                $likeImg = ($isLiked) ? 'liked.png' : 'like.png';

                echo    '
                        <div class="tweet_container">
                            <div class="flex-row">
                                <img class="profile-icon" src="images/profilepics/' . $user->profilePic . '">
                                <div>
                                    <div class="flex-column">
                                        <div>
                                            <span class="bold">' . $user->fullName() . '</span>
                                            <a href="userpage.php?user_id=' . $user->userId . '" class="bold">@' . $user->username . '</a>
                                        </div>
                                        <div>' . $tweet->tweetText . '</div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-end">
                                <div><i>' . $tweet->getTimeString() . '</i></div>
                                <div>
                                    <a href="#" class="reply" data-tweet-id="' . $tweet->tweetId . '">
                                        <img height="20px" width="20px" src="images/reply.png">
                                    </a> &nbsp;
                                    <a href="process/retweet_proc.php?tweet_id=' . $tweet->tweetId . '">
                                        <img height="20px" width="20px" src="images/retweet.png">
                                    </a> &nbsp;
                                    <a href="process/like_proc.php?tweet_id=' . $tweet->tweetId . '"><img height="20px" width="20px" src="images/' . $likeImg . '"></a> &nbsp;
                                </div>
                            </div>
                            <hr>
                        </div>';

            }
        }
    }

}