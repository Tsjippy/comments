<?php
namespace TSJIPPY\COMMENTS;
use TSJIPPY;
use TSJIPPY\ADMIN;

if ( ! defined('ABSPATH')) {
    exit;
}

class CommentWarningEmail extends ADMIN\MailSetting{

    public $commentData;

    public function __construct($commentData) {
        // call parent constructor
        parent::__construct('unapproved_comment', 'comments');

        $this->defaultSubject   = "A new comment has been made on %post_title%";

        $this->defaultMessage    = 'Dear all,<br><br>';
        $this->defaultMessage   .= "%comment_author% just left a comment on %post_title%.<br>";
        $this->defaultMessage     .= 'This is what the comment sais:<br>';
        $this->defaultMessage     .= '%comment_content%<br><br>';
        $this->defaultMessage     .= "Please approve this comment using <a href='%approve_link%'>this link</a><br>";
        $this->defaultMessage     .= "You can delete this comment using <a href='%delete_link%'>this link</a><br>";

        if (empty($commentData)) {
            return;
        }

        $postId                 = !empty($commentData['comment_post_ID']) ? $commentData['comment_post_ID'] : null;
        $postTitle              = get_the_title($postId);
        $authorId               = get_post_field('post_author', $postId);
        $author                 = get_userdata($authorId);

        if (!empty($commentData['comment_ID'])) {
            $commentId              = $commentData['comment_ID'];

            $approve_url            = admin_url("comment.php?c=$commentId&action=approvecomment");
            $delete_url             = admin_url("comment.php?c=$commentId&action=trashcomment");


            $this->replaceArray['%approve_link%']       = $approve_url;
            $this->replaceArray['%delete_link%']        = $delete_url;
        }

        $this->addUser($author);

        if (!empty($commentData['comment_author'])) {
            $this->replaceArray['%comment_author%']     = $commentData['comment_author'];
        }

        if (!empty($commentData['comment_content'])) {
            $this->replaceArray['%comment_content%']    = $commentData['comment_content'];
        }
        $this->replaceArray['%post_author%']        = $author;
        $this->replaceArray['%post_title%']         = $postTitle;
    }
}
