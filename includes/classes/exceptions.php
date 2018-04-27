<?php

class database_error extends Exception {
    private $display_message;

    public function __construct(
        int $errno
    ,   string $error
    ,   string $file
    ,   int $line
    ,   string $display_message
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        $this->display_message = $display_message;
        parent::__construct(
            'Database Error (' . $errno . ') '
            . $error . "[$file@$line]"
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()

    public function process_error(bool $api=false) {
        error_log($this->__toString());
        if ($api) {
            return $this->display_message;
        }
        echo $this->display_message;
        die();
    } // process_error()
}

class database_connection_error extends database_error {
    public function __construct(
        mysqli $mysqli
    ,   string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        parent::__construct(
            $mysqli->connect_errno
        ,   $mysqli->connect_error
        ,   $file
        ,   $line
        ,   'Could not connect to the database. Try again in a few minutes.'
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()
}

class database_statement_prepare_error extends database_error {
    public function __construct(
        mysqli $mysqli
    ,   string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        parent::__construct(
            $mysqli->errno
        ,   $mysqli->error
        ,   $file
        ,   $line
        ,   'Some unexpected error has occurred. Try again in a few minutes.'
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()
}

class database_bind_param_error extends database_error {
    public function __construct(
        mysqli $mysqli
    ,   string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        parent::__construct(
            $mysqli->errno
        ,   $mysqli->error
        ,   $file
        ,   $line
        ,   'Some unexpected error has occurred. Try again in a few minutes.'
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()
}

class database_statement_execute_error extends database_error {
    public function __construct(
        mysqli $mysqli
    ,   string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        parent::__construct(
            $mysqli->errno
        ,   $mysqli->error
        ,   $file
        ,   $line
        ,   'Some unexpected error has occurred. Try again in a few minutes.'
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()
}

class database_get_result_error extends database_error {
    public function __construct(
        mysqli $mysqli
    ,   string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        parent::__construct(
            $mysqli->errno
        ,   $mysqli->error
        ,   $file
        ,   $line
        ,   'Some unexpected error has occurred. Try again in a few minutes.'
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()
}

class database_query_error extends database_error {
    public function __construct(
        mysqli $mysqli
    ,   string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        parent::__construct(
            $mysqli->errno
        ,   $mysqli->error
        ,   $file
        ,   $line
        ,   'Some unexpected error has occurred. Try again in a few minutes.'
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()
}

class database_no_results_error extends database_error {
    public function __construct(
        string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        parent::__construct(
            1
        ,   'Some results expected, but none found.'
        ,   $file
        ,   $line
        ,   'Some unexpected error has occurred. Try again in a few minutes.'
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()
}

class external_service_error extends Exception {
    private $display_message;

    public function __construct(
        string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        $this->display_message = 'Looks like our server needs some minor fixes.'
        .                        ' Please try again in a few minutes.';
        parent::__construct(
            'External Service Error '
            . "[$file@$line]"
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()

    public function process_error(bool $api=false) {
        error_log($this->__toString());
        echo $this->display_message;
        die();
    } // process_error()
}

class forbidden_access_error extends Exception {
    private $display_message;

    public function __construct(
        string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        $this->display_message = 'You are not authorized to view this page.';
        parent::__construct(
            "Forbidden access request [$file@$line]"
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()

    public function process_error(bool $api=false) {
        http_response_code(403);
        if ($api) {
            return $this->display_message;
        }
        echo $this->display_message;
        die();
    } // process_error()
}

class invalid_request_method_error extends Exception {
    private $display_message;

    public function __construct(
        string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        $this->display_message = 'Server could not recognize the request '
        .                        'method.';
        parent::__construct(
            "Invalid request method [$file@$line]"
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()

    public function process_error(bool $api=false) {
        http_response_code(501);
        if ($api) {
            return $this->display_message;
        }
        echo $this->display_message;
        die();
    } // process_error()
}

class page_not_found_error extends Exception {
    private $display_message;

    public function __construct(
        string $file
    ,   int $line
    ,   int $code = 0
    ,   Exception $previous = null
    ) {
        $this->display_message = 'The page you are looking for does not exist.';
        parent::__construct(
            "404, Page not found [$file@$line]"
        ,   $code
        ,   $previous
        );
    } // __construct()

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    } // __toString()

    public function process_error(bool $api=false) {
        http_response_code(404);
        if ($api) {
            return $this->display_message;
        }
        echo $this->display_message;
        die();
    } // process_error()
}
?>