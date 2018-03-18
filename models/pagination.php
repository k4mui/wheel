<?php

class pagination {
    private $pages;
    private $last;

    private function __construct() {
        $pages = array();
    }
    public function get_last() {
        return $this->last;
    }
    private function set_last($l) {
        $this->last = $l;
    }
    public function get_pages() {
        return $this->pages;
    }
    private function set_pages($current_page) {
        $p = array();
        $p[] = $current_page - 1;
        $p[] = $current_page - 2;
        $p[] = $current_page + 1;
        $p[] = $current_page + 2;
        foreach($p as $v) {
            if ($v > 1 && $v < $this->last) {
                $this->pages[] = $v;
            }
        }
    }
    public static function get_instance($total_records, $current_page) {
        $last = ((int)($total_records/15)) + 1;
        if ($current_page < 1 || $current_page > $last) {
            return null;
        }
        $ins = new self();
        $ins->set_last($last);
        $ins->set_pages($current_page);
        return $ins;
    }
}

?>