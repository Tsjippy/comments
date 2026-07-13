<?php

namespace TSJIPPY\COMMENTS;

use TSJIPPY;
use TSJIPPY\ADMIN;

if (! defined('ABSPATH')) {
    exit;
}

class AdminMenu extends ADMIN\SubAdminMenu
{

    /**
     * AdminMenu constructor.
     *
     * @param array $settings The settings for the plugin
     * @param string $name The name of the plugin
     */
    public function __construct($settings, $name)
    {
        parent::__construct($settings, $name);
    }

    /**
     * Add the settings page to the admin menu
     *
     * @param string $parent The parent menu slug
     * 
     * @return bool True if the settings page was added, false otherwise
     */
    public function settings($parent)
    {
        TSJIPPY\addElement('label', $parent, [], "Which post types should have comments allowed by default?");

        TSJIPPY\addElement('br', $parent);

        foreach (get_post_types(['public' => true]) as $type) {
            $attributes = [
                'type'  => 'checkbox',
                'name'  => "posttypes[$type]",
                'value' => 1
            ];

            if ( isset($this->settings['posttypes'][$type]) ) {
                $attributes['checked'] = 'checked';
            }

            $label  = TSJIPPY\addElement('label', $parent, [], $type);
            TSJIPPY\addElement('input', $label, $attributes, '', 'afterBegin');

            TSJIPPY\addElement('br', $parent);
        }

        TSJIPPY\addElement('br', $parent);

        return true;
    }

    /**
     * Add the email settings page to the admin menu
     */
    public function emails($parent)
    {
        $tab      = 'approved-comment-email';
        // phpcs:ignore
        if (isset($_GET['second-tab'])) {
            // phpcs:ignore
            $tab  = TSJIPPY\sanitize($_GET['second-tab'], 'key');
        }

        $fakeData   = [
            'comment_post_ID'      => 1,
            'comment_author'       => '',
            'comment_author_email' => '',
            'comment_author_url'   => '',
            'comment_content'      => '',
            'comment_type'         => '',
            'comment_parent'       => 0,
            'user_id'              => 1,
            'comment_author_IP'    => '',
            'comment_agent'        => '',
            'comment_date'         => '',
            'comment_date_gmt'     => '',
            'comment_approved'     => 1,
            'filtered'             => true,
            'commentID'            => 1,
        ];

        ob_start();

        ?>
        <div class="tablink-wrapper">
            <button type="button" class="tablink <?php echo $tab == 'approved-comment-email' ? 'active' : ''; ?>" id="show-approved-comment-email" data-target="approved-comment-email">
                Approved e-mail
            </button>
            <button type="button" class="tablink <?php echo $tab == 'comment-warning-email' ? 'active' : ''; ?>" id="show-comment-warning-email" data-target="comment-warning-email">
                Notification e-mail
            </button>
            <button type="button" class="tablink <?php echo $tab == 'comment-reply-email' ? 'active' : ''; ?>" id="show-comment-reply-email" data-target="comment-reply-email">
                Reply e-mail
            </button>
        </div>

        <div id="approved-comment-email" class="tabcontent <?php echo $tab != 'approved-comment-email' ? 'hidden' : ''; ?>">
            <h4>
                Define the e-mail people get when someone left a comment to a page they created.
            </h4>
            <?php
            $email    = new ApprovedCommentEmail($fakeData);
            $email->printPlaceholders();
            $email->printInputs();
            ?>
        </div>

        <div id="comment-warning-email" class="tabcontent <?php echo $tab != 'comment-warning-email' ? 'hidden' : ''; ?>">
            <h4>
                Define the e-mail content managers get when a comment needs approval
            </h4>
            <?php
            $email    = new CommentWarningEmail($fakeData);
            $email->printPlaceholders();
            $email->printInputs();
            ?>
        </div>

        <div id="comment-reply-email" class="tabcontent <?php echo $tab != 'comment-reply-email' ? 'hidden' : ''; ?>">
            <h4>
                Define the e-mail people get when someone replies to their comment
            </h4>
            <?php
            $email    = new CommentReplyEmail($fakeData);
            $email->printPlaceholders();
            $email->printInputs();
            ?>
        </div>

        <?php
        TSJIPPY\addRawHtml(ob_get_clean(), $parent, 'beforeEnd');

        return true;
    }

    /**
     * Add the data page to the admin menu
     *
     * @param string $parent The parent menu slug
     * 
     * @return bool True if the data page was added, false otherwise
     */
    public function data($parent)
    {
        return false;
    }

    /**
     * Add the functions page to the admin menu
     *
     * @param string $parent The parent menu slug
     * 
     * @return bool True if the functions page was added, false otherwise
     */
    public function functions($parent)
    {
        return false;
    }
}
