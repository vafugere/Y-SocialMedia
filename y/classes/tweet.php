<?php
class Tweet {
    private $tweetId;
    private $tweetText;
    private $userId;
    private $originalTweetId;
    private $replyToTweetId;
    private $date;

    public function __get($property) {
        return $this->$property;
    }
    public function __set($property, $value) {
        $this->$property = $value;
    }
    public function __construct($tweetId, $tweetText, $userId, $originalTweetId, $replyToTweetId, $date) {
        $this->tweetId = $tweetId;
        $this->tweetText = $tweetText;
        $this->userId = $userId;
        $this->originalTweetId = $originalTweetId;
        $this->replyToTweetId = $replyToTweetId;
        $this->date = $date;
    }
    public function getTimeString() {
        $timezone = new DateTimeZone('America/Halifax');
        $currentTime = new DateTime();
        $tweetTime = new DateTime($this->date, $timezone);
        $interval = $tweetTime->diff($currentTime);
        $time = "just now";
        $timeUnits = [
            'year' => $interval->y,
            'month' => $interval->m,
            'day' => $interval->d,
            'hour' => $interval->h,
            'minute' => $interval->i,
        ];
        foreach ($timeUnits as $unit => $value) {
            if ($value > 0) {
                $time = $value . ' ' . $unit . ($value > 1 ? 's' : '') . ' ago';
                break;
            }
        }
        return $time;
    }
    public static function getTweetById($con, $tweetId) {
        $stmt = $con->prepare('SELECT * FROM tweets WHERE tweet_id = ?');
        $stmt->bind_param('i', $tweetId);
        $stmt->execute();
        $stmt->bind_result($tweetId, $tweetText, $userId, $originalTweetId, $replyToTweetId, $date);
        if ($stmt->fetch()) {
            $tweet = new Tweet($tweetId, $tweetText, $userId, $originalTweetId, $replyToTweetId, $date);
        } else {
            $tweet = null;
        }
        $stmt->close();
        return $tweet;
    }
    public static function insertTweet($con, $tweetText, $userId) {
        $stmt = $con->prepare('INSERT INTO `tweets` (`tweet_text`, `user_id`) VALUES (?,?)');
        $stmt->bind_param('si', $tweetText, $userId);
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: ../index.php');
            exit;
        } else {
            $stmt->close();
            $msg = 'An unexpected error has occured, please try again';
            header('Location: ../index.php?message=' . urlencode($msg));
            exit;
        }
    }
    public static function insertRetweet($con, $userId, $originalTweetId) {
        $stmt = $con->prepare('INSERT INTO `tweets` (`user_id`, `original_tweet_id`) VALUES (?,?)');
        $stmt->bind_param('ii', $userId, $originalTweetId);
        if ($stmt->execute()) {
            $stmt->close();
            Tweet::redirectSuccessful();
        } else {
            $stmt->close();
            Tweet::redirectUnsuccessful();
        }
    }
    public static function insertReply($con, $tweetText, $userId, $replyToTweetId) {
        $stmt = $con->prepare('INSERT INTO `tweets` (`tweet_text`, `user_id`, `reply_to_tweet_id`) VALUES (?,?,?)');
        $stmt->bind_param('sii', $tweetText, $userId, $replyToTweetId);
        $stmt->execute();
        $stmt->close();
    }
    public static function getReplies($con, $tweetId) {
        $stmt = $con->prepare('SELECT tweet_id, tweet_text, user_id, original_tweet_id, reply_to_tweet_id, date_created 
            FROM tweets WHERE reply_to_tweet_id = ? ORDER BY date_created LIMIT 5');
        $stmt->bind_param('i', $tweetId);
        $stmt->execute();
        $stmt->bind_result($id, $tweetText, $userId, $originalTweetId, $replyToTweetId, $date);
        $tweets = [];
        while ($stmt->fetch()) {
            $tweets[] = new Tweet($id, $tweetText, $userId, $originalTweetId, $replyToTweetId, $date);
        }
        $stmt->close();
        return $tweets;
    }
    public static function getTweets($con, $userId) {
        $stmt = $con->prepare('SELECT tweet_id, tweet_text, user_id, original_tweet_id, reply_to_tweet_id, date_created
            FROM tweets
            WHERE reply_to_tweet_id = 0
            AND user_id = ?
            ORDER BY date_created DESC');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($tweetId, $tweetText, $userId, $originalTweetId, $replyToTweetId, $date);
        $tweets = [];
        while ($stmt->fetch()) {
            $tweets[] = new Tweet($tweetId, $tweetText, $userId, $originalTweetId, $replyToTweetId, $date);
        }
        $stmt->close();
        return $tweets;
    }
    public static function isLiked($con, $tweetId, $userId) {
        $stmt = $con->prepare('SELECT like_id FROM likes WHERE tweet_id = ? AND user_id = ?');
        $stmt->bind_param('ii', $tweetId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $isLiked = $result->num_rows > 0;
        $stmt->close();
        return $isLiked;
    }
    public static function toggleLike($con, $tweetId, $userId) {
        $isLiked = Tweet::isLiked($con, $tweetId, $userId);

        if ($isLiked) {
            $stmt = $con->prepare('DELETE FROM likes WHERE tweet_id = ? AND  user_id = ?');
            $stmt->bind_param('ii', $tweetId, $userId);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $con->prepare('INSERT INTO `likes` (`tweet_id`, `user_id`) VALUES (?,?)');
            $stmt->bind_param('ii', $tweetId, $userId);
            $stmt->execute();
            $stmt->close();
        }
        Tweet::redirectSuccessful();
    }
    public static function displayTweets ($con, $tweets) {
        $loggedUser = $_SESSION['userId'];
        foreach ($tweets as $tweet) {    
            $user = User::getUserById($con, $tweet->userId);
            $isLiked = Tweet::isLiked($con, $tweet->tweetId, $loggedUser);
            $likeImg = ($isLiked) ? 'liked.png' : 'like.png';

            if ($tweet->originalTweetId != 0) {
            $originalTweet = Tweet::GetTweetById($con, $tweet->originalTweetId);
            $originalUser = User::GetUserById($con, $originalTweet->userId);
           

            # Retweets
            echo    '<div class="tweet_container">
                        <div class="flex-row">
                            <img class="profile-icon" src="images/profilepics/' . $originalUser->profilePic . '">
                            <div>
                                <div class="flex-column">
                                    <div class="flex-between">
                                        <div>
                                            <span class="bold">' . $originalUser->fullName() . '</span>
                                            <a href="userpage.php?user_id=' . $originalUser->userId . '" class="bold">@' . $originalUser->username . '</a>
                                        </div>
                                        <div>
                                            <i>Retweeted by:
                                            <a href="userpage.php?user_id=' . $user->userId . '" class="bold">@' . $user->username . '</a>
                                            </i>
                                        </div>
                                    </div>
                                    <div>' . $originalTweet->tweetText . '</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-end">
                            <div><i>' . $tweet->getTimeString() . '</i></div>
                            <div>
                                <a href="#" class="reply" data-tweet-id="' . $tweet->tweetId . '">
                                    <img height="20px" width="20px" src="images/reply.png">
                                </a> &nbsp;
                                <a href="process/retweet_proc.php?tweet_id=' . $tweet->originalTweetId . '">
                                    <img height="20px" width="20px" src="images/retweet.png">
                                </a> &nbsp;
                                <a href="process/like_proc.php?tweet_id=' . $tweet->tweetId . '"><img height="20px" width="20px" src="images/' . $likeImg . '"></a> &nbsp;
                            </div>
                        </div>
                        <hr>
                    </div>';
            } else {
                # Original Tweets
                echo    '<div class="tweet_container">
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
    public static function redirectSuccessful() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    public static function redirectUnsuccessful() {
        $msg = 'An unexpected error has occured, please try again';
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
            $separator = (parse_url($referer, PHP_URL_QUERY)) ? '&' : '?';
            header('Location: ' . $referer . $separator . 'message=' . urlencode($msg));
        } else {
            header('Location: ../index.php?message=' . urlencode($msg));
        }
        exit;
    }

}

