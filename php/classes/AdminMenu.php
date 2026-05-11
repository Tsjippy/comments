<?php
namespace TSJIPPY\COMMENTS;
use TSJIPPY;
use TSJIPPY\ADMIN;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminMenu extends ADMIN\SubAdminMenu{

    /**
     * AdminMenu constructor.
     * 
     * @param array $settings The settings for the plugin
     * @param string $name The name of the plugin
     */
    public function __construct($settings, $name){
        parent::__construct($settings, $name);
    }

    public function settings($parent){
        TSJIPPY\addElement('label', $parent, [], "Which post types should have comments allowed by default?");

        TSJIPPY\addElement('br', $parent);

        foreach(get_post_types() as $type){
            $attributes = [
                'type'  => 'checkbox',
                'name'  => 'posttypes[]',
                'value' => $type
            ];

            if(
                !empty($this->settings['posttypes']) && 
                is_array($this->settings['posttypes']) && 
                in_array($type, $this->settings['posttypes'])
            ){
                $attributes['checked'] = 'checked';
            }

            $label  = TSJIPPY\addElement('label', $parent, [], $type);
            TSJIPPY\addElement('input', $label, $attributes, '', 'afterBegin');

            TSJIPPY\addElement('br', $parent);
        }

        TSJIPPY\addElement('br', $parent);
        
        return true;
    }

    public function emails($parent){
        $tab      = 'approved-comment-email';
        if(isset($_GET['second-tab'])){
            $tab  = sanitize_key($_GET['second-tab']);
        }
        
        ob_start();

        ?>
        <div class="tablink-wrapper">
            <button type="button" class="tablink <?php echo $tab == 'approved-comment-email' ? 'active' : '';?>" id="show-approved-comment-email" data-target="approved-comment-email">
                Approved comment e-mail
            </button>
            <button type="button" class="tablink <?php echo $tab == 'comment-warning-email' ? 'active' : '';?>" id="show-comment-warning-email" data-target="comment-warning-email">
                Comment warning e-mail
            </button>
            <button type="button" class="tablink <?php echo $tab == 'comment-reply-email' ? 'active' : '';?>" id="show-comment-reply-email" data-target="comment-reply-email">
                Comment reply e-mail
            </button>
        </div>

        <div id="approved-comment-email" class="tabcontent <?php echo $tab != 'approved-comment-email' ? 'hidden' : '';?>">
            <h4>Define the e-mail people get when someone left a comment to a page they created.</h4>
            <?php
            $email    = new ApprovedCommentEmail([]);
            $email->printPlaceholders();
            $email->printInputs();
            ?>
        </div>

        <div id="comment-warning-email" class="tabcontent <?php echo $tab != 'comment-warning-email' ? 'hidden' : '';?>">	
            <h4>Define the e-mail content managers get when a comment needs approval</h4>
            <?php
            $email    = new CommentWarningEmail([]);
            $email->printPlaceholders();
            $email->printInputs();
            ?>
        </div>

        <div id="comment-reply-email" class="tabcontent <?php echo $tab != 'comment-reply-email' ? 'hidden' : '';?>">
            <h4>Define the e-mail people get when someone replies to their comment</h4>
            <?php
            $email    = new CommentReplyEmail([]);
            $email->printPlaceholders();
            $email->printInputs();
            ?>
        </div>

        <?php
        TSJIPPY\addRawHtml(ob_get_clean(), $parent, 'beforeEnd');

        return true;
    }

    public function data($parent){
        return false;
    }

    public function functions($parent){
        return false;
    }

}