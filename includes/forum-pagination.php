<?php

if (!defined('ABSPATH')) exit;

class AsgarosForumPagination {
    private $asgarosforum = null;

    public function __construct($object) {
        $this->asgarosforum = $object;
    }

    public function renderTopicOverviewPagination($topicID) {
        $count = $this->asgarosforum->db->get_var($this->asgarosforum->db->prepare("SELECT COUNT(*) FROM {$this->asgarosforum->tables->posts} WHERE parent_id = %d;", $topicID));
        $num_pages = ceil($count / $this->asgarosforum->options['posts_per_page']);

        // Only show pagination when there is more than one page.
        if ($num_pages > 1) {
            echo '&nbsp;&middot;&nbsp;<div class="pages">';

            if ($num_pages <= 5) {
                for ($i = 1; $i <= $num_pages; $i++) {
                    $link = $this->asgarosforum->get_link('topic', $topicID, array('part' => $i));

                    echo '<a href="'.$link.'">'.number_format_i18n($i).'</a>';
                }
            } else {
                for ($i = 1; $i <= 3; $i++) {
                    $link = $this->asgarosforum->get_link('topic', $topicID, array('part' => $i));

                    echo '<a href="'.$link.'">'.number_format_i18n($i).'</a>';
                }

                $link = $this->asgarosforum->get_link('topic', $topicID, array('part' => $num_pages));
                echo '&raquo;<a href="'.$link.'">'.__('Last', 'asgaros-forum').'</a>';
            }

            echo '</div>';
        }
    }

    public function renderPagination($location, $sourceID = false) {
        $current_page = $this->asgarosforum->current_page;
        $num_pages = 0;
        $select_url = $this->asgarosforum->get_link('current', false, false, '', false);

        if ($location == $this->asgarosforum->tables->posts) {
            $count = $this->asgarosforum->db->get_var($this->asgarosforum->db->prepare("SELECT COUNT(*) FROM {$location} WHERE parent_id = %d;", $sourceID));
            $num_pages = ceil($count / $this->asgarosforum->options['posts_per_page']);
        } else if ($location == $this->asgarosforum->tables->topics) {
            $count = $this->asgarosforum->db->get_var($this->asgarosforum->db->prepare("SELECT COUNT(*) FROM {$location} WHERE parent_id = %d AND status LIKE %s;", $sourceID, "normal%"));
            $num_pages = ceil($count / $this->asgarosforum->options['topics_per_page']);
        } else if ($location === 'search') {
            $categories = $this->asgarosforum->content->get_categories();
            $categoriesFilter = array();

            foreach ($categories as $category) {
                $categoriesFilter[] = $category->term_id;
            }

            $where = 'AND f.parent_id IN ('.implode(',', $categoriesFilter).')';
            $shortcodeSearchFilter = $this->asgarosforum->shortcode->shortcodeSearchFilter;

            $query_match_name = "SELECT search_name.id AS topic_id FROM {$this->asgarosforum->tables->topics} AS search_name WHERE MATCH (search_name.name) AGAINST ('{$this->asgarosforum->search->search_keywords_for_query}*' IN BOOLEAN MODE)";
            $query_match_text = "SELECT search_text.parent_id AS topic_id FROM {$this->asgarosforum->tables->posts} AS search_text WHERE MATCH (search_text.text) AGAINST ('{$this->asgarosforum->search->search_keywords_for_query}*' IN BOOLEAN MODE)";
            $count = $this->asgarosforum->db->get_col("SELECT search_union.topic_id FROM (({$query_match_name}) UNION ({$query_match_text})) AS search_union, {$this->asgarosforum->tables->topics} AS t, {$this->asgarosforum->tables->forums} AS f WHERE search_union.topic_id = t.id AND t.parent_id = f.id {$where} {$shortcodeSearchFilter};");
            $count = count($count);
            $num_pages = ceil($count / $this->asgarosforum->options['topics_per_page']);
        } else if ($location === 'members') {
            // Count the users based on the filter.
            $count = 0;

            if ($this->asgarosforum->memberslist->filter_type === 'role') {
                $count = count($this->asgarosforum->permissions->get_users_by_role($this->asgarosforum->memberslist->filter_name));
            } else if ($this->asgarosforum->memberslist->filter_type === 'group') {
                $count = AsgarosForumUserGroups::countUsersOfUserGroup($this->asgarosforum->memberslist->filter_name);
            }

            $num_pages = ceil($count / $this->asgarosforum->options['members_per_page']);
        } else if ($location === 'activity') {
            $count = $this->asgarosforum->activity->load_activity_data(true);
            $num_pages = ceil($count / 50);
        } else if ($location === 'history') {
            $user_id = $this->asgarosforum->current_element;
            $count = $this->asgarosforum->profile->count_history_data($user_id);
            $num_pages = ceil($count / 50);
        }

        // Only show pagination when there is more than one page.
        if ($num_pages > 1) {
            $out = '<div class="pages">';
            $out .= __('Pages:', 'asgaros-forum');

            if ($num_pages <= 5) {
                for ($i = 1; $i <= $num_pages; $i++) {
                    if ($i == ($current_page + 1)) {
                        $out .= '<strong>'.number_format_i18n($i).'</strong>';
                    } else {
                        $link = add_query_arg('part', $i, $select_url);
                        $out .= '<a href="'.$link.'">'.number_format_i18n($i).'</a>';
                    }
                }
            } else {
                if ($current_page >= 3) {
                    $link = remove_query_arg('part', $select_url);
                    $out .= '<a href="'.$link.'">'.__('First', 'asgaros-forum').'</a>&laquo;';
                }

                for ($i = 2; $i > 0; $i--) {
                    if ((($current_page + 1) - $i) > 0) {
                        $link = add_query_arg('part', (($current_page + 1) - $i), $select_url);
                        $out .= '<a href="'.$link.'">'.number_format_i18n(($current_page + 1) - $i).'</a>';
                    }
                }

                $out .= '<strong>'.number_format_i18n($current_page + 1).'</strong>';

                for ($i = 1; $i <= 2; $i++) {
                    if ((($current_page + 1) + $i) <= $num_pages) {
                        $link = add_query_arg('part', (($current_page + 1) + $i), $select_url);
                        $out .= '<a href="'.$link.'">'.number_format_i18n(($current_page + 1) + $i).'</a>';
                    }
                }

                if ($num_pages - $current_page >= 4) {
                    $link = add_query_arg('part', $num_pages, $select_url);
                    $out .= '&raquo;<a href="'.$link.'">'.__('Last', 'asgaros-forum').'</a>';
                }
            }

            $out .= '</div>';
            return $out;
        } else {
            return false;
        }
    }
}
