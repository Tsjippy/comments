<?php

namespace TSJIPPY\COMMENTS;

use TSJIPPY;

if (! defined('ABSPATH')) {
    exit;
}

add_action('tsjippy-frontend-content-post-after-content', __NAMESPACE__ . '\afterPostContent', 20);
/**
 * Add the comments section to the frontend post content
 * 
 * @param   object    $object    The FrontEndContent instance
 */
function afterPostContent($object)
{
    $allowedPostTypes     = SETTINGS['posttypes'] ?? [];

    ?>
    <div 
        id="comments" 
        class="property frontend-form expand-wrapper
        <?php if(isset($allowedPostTypes[$object->postType])) echo 'hidden';
        echo esc_attr(implode(' ', $allowedPostTypes)); ?>"
    >
        <h4>
            Comments
            <button class="button small expand" type='button'>
                &#9660;
            </button>
        </h4>
        <label class="hidden expandable">
            <input type='checkbox' name='comments' value='allow' <?php echo comments_open($object->postId) ? 'checked' : ''; ?>>
            Allow comments
        </label>
    </div>
    <?php
}

/**
 * Allow comments
 * 
 * @param   \WP_Post    $post       The new or updated post
 * @param   object      $object     FrontEndContent Instance
 * @param   array       $request    The sanitized request data
 */
add_action('tsjippy-frontend-content-after-post-save', __NAMESPACE__ . '\afterPostSave', 999, 3);
/**
 * Allow comments
 * 
 * @param   \WP_Post    $post       The new or updated post
 * @param   object      $object     FrontEndContent Instance
 * @param   array       $request    The sanitized request data
 */
function afterPostSave($post, $object, $request)
{
    if (($request['comments'] ?? '')  == 'allow') {
        // Only update if the current post is closed for comments
        if ($post->comment_status != "open") {
            wp_update_post(
                array(
                    'ID'                => $post->ID,
                    'comment_status'    => 'open',
                ),
                false,
                false
            );
        }
    } elseif ($object->update && $post->comment_status == "open") {
        wp_update_post(
            array(
                'ID'                => $post->ID,
                'comment_status'    => 'closed'
            ),
            false,
            false
        );
    }
}
