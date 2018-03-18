<?php

class statistics {
    private $reply_count;
    private $discussion_count;
    private $image_count;
    private $image_size;
    private $da;

    function __construct(& $da) {
        $this->da = $da;
    }
    public function with_board_id() {}
    public function with_user_id() {}
}

?>