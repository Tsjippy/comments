<?php

namespace TSJIPPY\COMMENTS;

use TSJIPPY;
use TSJIPPY\ADMIN;

if (! defined('ABSPATH')) {
    exit;
}

class CommentReplyEmail extends ADMIN\MailSetting
{

    public $commentData;

    public function __construct($commentData)
    {
        // call parent constructor
        parent::__construct('replied_comment', 'comments');

        $this->defaultSubject   = "%comment_author% just replied to your comment at %post_title%";

        $this->defaultMessage    = 'Hi %first_name%,<br><br>';
        $this->defaultMessage   .= "%comment_author% just left a comment on %post_title%.<br>";
        $this->defaultMessage     .= 'This is what the comment sais:<br>';
        $this->defaultMessage     .= '%comment_content%<br><br>';
        $this->defaultMessage     .= "You can reply to this comment using <a href='%reply_link%'>this link</a> if you want. ";

        if (empty($commentData)) {
            return;
        }

        $postId                 = !empty($commentData['comment_post_ID']) ? $commentData['comment_post_ID'] : null;
        $postTitle              = get_the_title($postId);

        if (!empty($commentData['comment_parent'])) {
            $parentComment          = get_comment($commentData['comment_parent']);
            $parentAuthor           = get_userdata($parentComment->user_id);

            $this->addUser($parentAuthor);
        }

        if (!empty($commentData['commentID'])) {
            $replyLink              = get_permalink($postId) . '#' . $commentData['commentID'];

            $this->replaceArray['%reply_link%']         = $replyLink;
        }

        if (!empty($commentData['comment_author'])) {
            $this->replaceArray['%comment_author%']     = $commentData['comment_author'];
        }

        if (!empty($commentData['comment_content'])) {
            $this->replaceArray['%comment_content%']    = $commentData['comment_content'];
        }

        $this->replaceArray['%post_title%']         = $postTitle;
    }
}
