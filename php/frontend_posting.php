<?php
namespace TSJIPPY\COMMENTS;
use TSJIPPY;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('tsjippy_frontend_post_after_content', __NAMESPACE__.'\afterPostContent');
function afterPostContent($frontendcontend){
    $allowedPostTypes     = SETTINGS['posttypes'] ?? [];

    ?>
    <div id="comments" class="property frontend-form <?php echo in_array($frontendcontend->postType, $allowedPostTypes) ? 'hidden' : ''; echo esc_attr(implode(' ', $allowedPostTypes));?>">
        <h4>Comments</h4>
        <label>
            <input type='checkbox' name='comments' value='allow' <?php echo comments_open($frontendcontend->postId) ? 'checked' : ''; ?>>
            Allow comments
        </label>
    </div>
    <?php
}

// Allow comments
add_action('tsjippy_after_post_save', __NAMESPACE__.'\afterPostSave', 999, 2);
function afterPostSave($post, $frontEndPost){
    if(
        isset($_POST['comments']) &&        // There is a comment setting
        $_POST['comments'] == 'allow'      // and the value is allow
    ){
        // Only update if the current post is closed for comments
        if($post->comment_status != "open"){     
            wp_update_post(
                array(
                    'ID'                => $post->ID,
                    'comment_status'    => 'open',
                ),
                false,
                false
            );
        }
    }elseif($frontEndPost->update && $post->comment_status == "open"){
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