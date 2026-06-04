<?php

namespace TSJIPPY\COMMENTS;

use TSJIPPY;
use TSJIPPY\ADMIN;

if (! defined('ABSPATH')) {
    exit;
}

class ApprovedCommentEmail extends ADMIN\MailSetting
{

    public $commentData;

    public function __construct($commentData)
    {
        // call parent constructor
        parent::__construct('approved_comment', 'comments');

        $this->defaultSubject    = "A new comment has been made on %post_title%";

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
        $authorId               = get_post_field('post_author', $postId);
        $author                 = get_userdata($authorId);
        $replyLink              = get_permalink($postId) . '#';
        if (!empty($commentData['comment_ID'])) {
            $replyLink .= $commentData['comment_ID'];
        }

        $this->addUser($author);

        if (!empty($commentData['comment_author_email'])) {
            $this->replaceArray['%comment_author%']     = $commentData['comment_author'];
        }

        if (!empty($commentData['comment_content'])) {
            $this->replaceArray['%comment_content%']    = $commentData['comment_content'];
        }

        if ($author) {
            $this->replaceArray['%post_author%']        = $author->display_name;
        }
        $this->replaceArray['%post_title%']         = $postTitle;
        $this->replaceArray['%reply_link%']         = $replyLink;
    }
}
