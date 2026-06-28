<?php

namespace TSJIPPY\COMMENTS;

use TSJIPPY;

if (! defined('ABSPATH')) {
    exit;
}

add_action('comment_post', __NAMESPACE__ . '\commentPost', 10, 3);
function commentPost($commentID, $approved, $commentdata)
{
    $commentdata['commentID']   = $commentID;

    if ($approved) {
        // Comment reply
        if ($commentdata['comment_parent'] > 0) {
            $email                  = new CommentReplyEmail($commentdata);

            $parentComment          = get_comment($commentdata['comment_parent']);
            $parentCommentAuthor    = get_userdata($parentComment->user_id);

            $to                     = $parentCommentAuthor->user_email;
            // Send e-mail to the post author
        } else {
            $email                  = new ApprovedCommentEmail($commentdata);

            $postId                 = $commentdata['comment_post_ID'];
            $authorId               = get_post_field('post_author', $postId);
            $author                 = get_userdata($authorId);
            $to                     = $author->user_email;
        }
        // Send e-mail to content managers
    } else {
        $to                     = '';
        $users                  = get_users(['role'    => 'editor']);
        foreach ($users as $user) {
            $to .= $user->user_email . ', ';
        }
        $email                  = new CommentWarningEmail($commentdata);
    }

    $email->filterMail();
    $subject                = $email->subject;
    $message                = $email->message;
    wp_mail($to, $subject, $message);
}

/**
 * Filter whether comments are open on post save
 *
 * @param string $status       Default status for the given post type,
 *                             either 'open' or 'closed' .
 * @param string $postType    Post type. Default is `post`.
 */
add_filter('get_default_comment_status', __NAMESPACE__ . '\defaultStatus', 1, 2);
function defaultStatus($status, $postType)
{
    $allowedPostTypes     = SETTINGS['posttypes'] ?? ['post' => 1];
    if (isset($allowedPostTypes[$postType])) {
        return 'open';
    }

    return $status;
}
