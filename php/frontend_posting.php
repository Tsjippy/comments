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
    <tbody
        id="comments"
        class="property frontend-form expand-wrapper 
        <?php if (isset($allowedPostTypes[$object->postType])) echo 'hidden';
        echo esc_attr(implode(' ', array_keys($allowedPostTypes))); ?>">
        <tr>
            <td>
                <h4>
                    Comments
                </h4>
            </td>
            <td>
                <button class="button small expand" type='button'>
                    &#9660;
                </button>
            </td>
        </tr>

        <tr>
            <td class="hidden expandable" collspan=2>
                <input type='checkbox' name='comments' value='allow' <?php if(comments_open($object->postId)) echo 'checked'; ?>>
                Allow comments
            </td>
        </tr>
    </tbody>
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
