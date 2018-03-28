<?php
require_once(__DIR__."/board.php");
require_once(__DIR__."/discussion.php");
require_once(__DIR__."/user.php");


class data_access {
  private $mysqli;


  private function __construct(& $mysqli) {
    $this->mysqli = $mysqli;
  } // function: __construct

  function __destruct() {
    $closeResults = $this->mysqli->close();
    if($closeResults === false) {
      echo "Could not close MySQL connection.";
    }
  } // function: __destruct

  public static function get_instance() {
    global $dbname, $dbpwd, $dbserver, $dbuser;
    mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);
    
    try {
      $mysqli = new mysqli($dbserver, $dbuser, $dbpwd, $dbname);
      $ins = new self($mysqli);
      return $ins;
    } catch (Exception $e) {
      echo $e;
      return null;
    }
  } // get_instance

  public function get_board($board_id) {
    $sql = 'SELECT id, title, fa_icon, is_locked FROM boards WHERE id = ?';
    $params = array('i', $board_id);
    $result = $this->prepared($sql, $params);
    return count($result) ? $result[0] : null;
  } // get_board
  
  public function get_boards() {
    $sql = 'SELECT id, title, fa_icon FROM boards';
    return $this->select($sql);
  } // get_boards

  public function get_board_rules($board_id) {
    $sql = "SELECT full_text FROM rules WHERE board_id = $board_id";
    $result = $this->select($sql);
    return $result[0]['full_text'];
  } // get_board_rules

  public function get_board_stats($board_id) {
    $stats = array(
      'reply_count' => $this->count_all('replies', 'INNER JOIN discussions ON discussions.id = replies.discussion_id INNER JOIN boards ON boards.id = discussions.board_id WHERE boards.id = '.$board_id),
      'discussion_count' => $this->count_all('discussions', 'INNER JOIN boards ON discussions.board_id = boards.id WHERE boards.id = '.$board_id),
      'image_count' => $this->count_all('images', 'INNER JOIN replies ON replies.image_id = images.id INNER JOIN discussions ON discussions.id = replies.discussion_id WHERE discussions.board_id = '.$board_id)
    );
    return $stats;
  } // get_board_stats

  public function get_discussions($board_id, $archive=0) {
    $sql = "SELECT d.id,
                  d.title,
                  d.full_text,
                  d.creation_timestamp,
                  i.filename,
                  (SELECT COUNT(*)+1
                   FROM images,
                        replies
                   WHERE replies.discussion_id = d.id AND
                         replies.image_id = images.id) AS image_count,
                  (SELECT COUNT(*)
                   FROM replies
                   WHERE replies.discussion_id = d.id) AS reply_count,
                  (SELECT creation_timestamp
                   FROM replies
                   WHERE replies.discussion_id = d.id
                   ORDER BY id DESC
                   LIMIT 1) AS last_reply_timestamp
          FROM discussions AS d,
                images AS i
          WHERE d.board_id = ? AND
                d.image_id = i.id AND
                d.archived = $archive
          ORDER BY d.id DESC";
    $params = array('i', $board_id);
    return $this->prepared($sql, $params);
  } // function: get_discussions

  public function get_discussion($discussion_id) {
    $sql = "SELECT d.author_id,
                   d.archived,
                   d.id,
                   d.title,
                   d.creation_timestamp,
                   d.full_text,
                   d.board_id,
                   i.filename,
                   b.fa_icon,
                   b.title AS board_title
            FROM discussions AS d,
                 images AS i,
                 boards AS b
            WHERE d.id = ? AND
                  d.image_id = i.id AND
                  b.id = d.board_id";
    $params = array('i', $discussion_id);
    $result = $this->prepared($sql, $params);
    return count($result) ? $result[0] : null;
  } // function: get_discussion

  public function get_recent_discussion($board_id) {
    $sql = "SELECT discussions.id,
                   discussions.creation_timestamp,
                   discussions.title,
                   discussions.full_text,
                   images.filename
            FROM discussions
            INNER JOIN images
            ON images.id = discussions.image_id
            WHERE discussions.board_id = $board_id
            ORDER BY discussions.id DESC
            LIMIT 1";
    $result = $this->select($sql);
    return count($result) ? $result[0] : $result;
  } // get_recent_discussion

  public function get_recent_discussions() {
    $sql = "SELECT d.id,
                   d.title,
                   d.creation_timestamp,
                   i.filename,
                   b.title AS board_title,
                   b.id AS board_id
            FROM boards AS b,
                 discussions AS d,
                 images AS i
            WHERE d.image_id = i.id AND
                  d.archived = 0 AND
                  b.id = d.board_id
            ORDER BY d.id DESC
            LIMIT 5";
    return $this->select($sql);
  } // function: get_recent_discussions

  public function get_replies($discussion_id) {
    $rows = null;
    try {
      $sql = "SELECT r.id,
                     r.full_text,
                     r.creation_timestamp,
                     i.filename,
                     r.author_id
              FROM replies AS r
              LEFT JOIN images AS i
              ON r.image_id = i.id
              WHERE r.discussion_id = ?";
      $stmt = $this->mysqli->prepare($sql);
      $stmt->bind_param('i', $discussion_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $rows = array();
      while($row = $result->fetch_assoc()) {
        $rows[] = $row;
      }
      $result->free();
      $stmt->close();
    } catch(Exception $e) {
      error_log('[DBError] '.$e->getMessage());
    } finally {
      return $rows;
    }
  } // function: get_replies

  public function get_site_stats() {
    $stats = array(
      'reply_count' => $this->count_all('replies'),
      'discussion_count' => $this->count_all('discussions'),
      'image_count' => $this->count_all('images')
    );
    return $stats;
  } // function: get_site_stats

  private function count_all($table_name, $condition=null) {
    $sql = "SELECT COUNT(*) AS c FROM $table_name $condition";
    $result = $this->select($sql);
    return $result[0]['c'];
  } // count_all

  private function sum($table_name, $column_name, $condition=null) {
    $sql = "SELECT IFNULL(SUM($column_name), 0) AS s FROM $table_name $condition";
    $result = $this->select($sql);
    return $result[0]['s'];
  } // sum

  public function get_user($email_address) {
    $sql = "SELECT users.id,
                   users.role,
                   users.password_hash,
                   users.account_status,
                   moderations.board_id
            FROM users
            LEFT JOIN moderations
            ON users.id = moderations.user_id
            WHERE users.email_address=?";
    $params = array('s', $email_address);
    return $this->prepared($sql, $params);
  } // function: get_user

  public function insert_discussion($title,
                                    $full_text,
                                    $image_id,
                                    $user_id,
                                    $board_id) {
    $sql = 'INSERT INTO discussions (title,
                                     full_text,
                                     author_id,
                                     image_id,
                                     board_id)
            VALUES(?, ?, ?, ?, ?)';
    $params = array(
      'ssiii',
      $title,
      $full_text,
      $user_id,
      $image_id,
      $board_id
    );
    return $this->prepared($sql, $params, false);
  } // insert_discussion

  private function get_extension($mime) {
    switch($mime) {
      case 'image/gif':
      case 'image/gi_':
        return 'gif';
      case 'image/bmp':
      case 'image/x-bmp': 
      case 'image/x-bitmap':
      case 'image/x-xbitmap': 
      case 'image/x-win-bitmap': 
      case 'image/x-windows-bmp': 
      case 'image/ms-bmp': 
      case 'image/x-ms-bmp':
        return 'bmp';
      case 'image/png':
        return 'png';
      case 'image/jpeg':
      case 'image/jpe_': 
      case 'image/pjpeg': 
      case 'image/vnd.swiftview-jpeg':
        return 'jpeg';
       case 'image/jpg':
       case 'image/jp_':
       case 'image/pipeg':
        return 'jpg';
      default:
        return false;
    }
  }

  public function insert_image($image) {
    $ext = $this->get_extension($image['type']);
    if (!$ext) {
      return null;
    }
    $filename = md5_file($image['tmp_name']).'.'.$ext;
    $image_dir= '/home/obi/dev/localhost/wheel/images/usercontents/';

    if (!file_exists("$image_dir$filename")) { // file not in server already
      if(!move_uploaded_file($image['tmp_name'], "$image_dir$filename")) { // cannot move file to dest
        return null;
      }
    }

    $sql = 'INSERT INTO images(filename, size) VALUES (?, ?)';
    $params = array('si', $filename, $image['size']);

    return $this->prepared($sql, $params, false);
  } // insert_image

  public function insert_reply($full_text,
                               $image_id,
                               $user_id,
                               $discussion_id) {
    $sql = 'INSERT INTO replies (full_text,
                                 author_id,
                                 image_id,
                                 discussion_id)
            VALUES(?, ?, ?, ?)';
    $params = array(
      'siii',
      $full_text,
      $user_id,
      $image_id,
      $discussion_id
    );
    return $this->prepared($sql, $params, false);
  } // insert_reply

  public function insert_user($email_address, $password) {
    $sql = "INSERT INTO users(email_address, password_hash) VALUES(?, ?)";
    $result = true;
    try {
      $stmt = $this->mysqli->prepare($sql);
      $stmt->bind_param("ss", $email_address, $hash);
      $hash = md5($password);
      $result = $stmt->execute();
      $stmt->close();
    } catch(Exception $e) {
      $result = false;
    } finally {
      return $result;
    }
  } // insert_user

  private function prepared($sql, $params, $results=true) {
    $rows = null;
    $error = false;
    $stmt = $this->mysqli->prepare($sql);
    if ($stmt) {
      $tmp = array();
      foreach($params as $k=>$v) {
        $tmp[$k] = & $params[$k];
      }
      if (call_user_func_array(array($stmt, 'bind_param'), $tmp)) {
        if($stmt->execute()) {
          if ($results) {
            $rows = array();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
              $rows[] = $row;
            }
            $result->free();
            $stmt->close();
          } else {
            $rows = $stmt->insert_id;
            $stmt->close();
          }
        } else {
          $error = true;
        }
      } else {
        $error = true;
      }
    } else {
      $error = true;
    }

    if ($error) {
      error_log('[DBError] '.$this->mysqli->error);
    }
    return $rows;
  } // prepared

  private function select($sql) {
    $rows = array();
    $error = false;
    $result = $this->mysqli->query($sql);
    if ($result) {
      while($row = $result->fetch_assoc()) {
        $rows[] = $row;
      }
      $result->free();
    } else {
      $error = true;
    }

    if ($error) {
      error_log('[DBError] '.$this->mysqli->error);
    }
    return $rows;
  } // select
} // class: data_access

?>
