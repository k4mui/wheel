<?php
require_once(__DIR__."/board.php");
require_once(__DIR__."/discussion.php");
require_once(__DIR__."/user.php");

$dbname = 'wheelim';
$dbpwd = 'qwertY33';
$dbserver = '127.0.0.1';
$dbuser = 'root';

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

  public function get_categories($limit=null) {
    $sql = 'SELECT * FROM categories ';
    if ($limit) {
      $sql = "SELECT DISTINCT sq.category_name, sq.category_id
              FROM (SELECT categories.category_name,
                           categories.category_id,
                           SUM(pv.vc) as svc,
                           pv.country_code
                    FROM categories
                    LEFT JOIN posts
                      ON categories.category_id = posts.category_id
                    LEFT JOIN (SELECT SUM(post_views.view_count) AS vc,
                               post_views.post_id,
                               post_views.country_code
                                FROM post_views
                                GROUP BY post_views.country_code,
                               post_views.post_id) pv
                      ON pv.post_id = posts.post_id
                    GROUP BY categories.category_name, pv.country_code
                    ORDER BY pv.country_code = ? DESC,svc DESC) sq
                    LIMIT $limit";
      $params = array('s', $_SESSION['cc']);
      return $this->prepared($sql, $params);
    }
    return $this->select($sql);
  }

  public function get_discussion($did) {
    $sql = "SELECT posts.post_title,
                   attachments.attachment_name,
                   categories.category_name,
                   categories.category_id,
                   posts.post_text,
                   posts.author_id,
                   posts.submitted_ts,
                   posts.post_id,
                   posts.post_text
            FROM posts
            INNER JOIN categories
            ON categories.category_id = posts.category_id
            LEFT JOIN attachments
            ON attachments.attachment_id=posts.attachment_id
            WHERE posts.post_id = ?";
    $params = array('i', $did);
    $result = $this->prepared($sql, $params);
    return count($result) ? $result[0] : null;
  } // function: get_discussions

  public function get_discussions($page=null) {
    $sql = "SELECT posts.post_title,
                   attachments.attachment_name,
                   categories.category_name,
                   categories.category_id,
                   posts.post_text,
                   posts.author_id,
                   posts.submitted_ts,
                   posts.post_id
            FROM posts
            INNER JOIN categories
            ON categories.category_id = posts.category_id
            LEFT JOIN attachments
            ON attachments.attachment_id=posts.attachment_id
            WHERE posts.parent_post_id IS NULL
            ORDER BY posts.submitted_ts DESC";
    return $this->select($sql);
  } // function: get_discussions

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

  public function get_replies($did) {
    $sql = "SELECT attachments.attachment_name,
                   posts.post_text,
                   posts.author_id,
                   posts.submitted_ts,
                   posts.post_id
            FROM posts
            LEFT JOIN attachments
            ON posts.attachment_id = attachments.attachment_id
            WHERE posts.parent_post_id = ?
            ORDER BY posts.submitted_ts DESC";
    $params = array('i', $did);
    return $this->prepared($sql, $params);
  } // function: get_replies

  public function get_site_stats() {
    $stats = array(
      'reply_count' => $this->count_all('replies'),
      'discussion_count' => $this->count_all('discussions'),
      'image_count' => $this->count_all('images')
    );
    return $stats;
  } // function: get_site_stats

  public function get_reply_count($post_id) {
    $sql = "SELECT COUNT(post_id) AS reply_count FROM posts WHERE parent_post_id=?";
    $params = array('i', $post_id);
    return $this->prepared($sql, $params);
  }

  public function get_tags($count) {
    $sql = "SELECT * FROM post_tags LIMIT $count";
    return $this->select($sql);
  }

  public function get_tags_by_post_id($pid) {
    $sql = "SELECT * FROM post_tags WHERE post_id=?";
    $params = array('i', $pid);
    return $this->prepared($sql, $params);
  } // get_tags_by_post_id

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
    $params = array('s', strtolower($email_address));
    return $this->prepared($sql, $params);
  } // function: get_user

  public function get_users_cp() {
    $sql = "SELECT users.id,
                   users.email_address,
                   users.role,
                   users.account_status,
                   users.registration_timestamp,
                   moderations.board_id
            FROM users
            LEFT JOIN moderations
            ON users.id = moderations.user_id";
    return $this->select($sql);
  } // function: get_user

  public function insert_discussion($title,
                                    $full_text,
                                    $image_id,
                                    $user_id,
                                    $board_id) {
    $sql = 'INSERT INTO posts (post_title, post_text, attachment_id, category_id, author_id)
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

  public function insert_post($post_title, $post_text, $parent_post_id, $author_id, $attachement_id, $category_id) {
    $sql = 'INSERT INTO posts (post_title, post_text, parent_post_id, attachment_id, category_id, author_id)
            VALUES(?, ?, ?, ?, ?, ?)';
    $params = array('ssiiii',$post_title,$post_text,$parent_post_id,$attachement_id,$category_id,$author_id);
    return $this->prepared($sql, $params, false);
  } // insert_discussion

  public function insert_tags($post_id, $tags) {
    $sql = 'INSERT INTO post_tags VALUES (?, ?)';
    $params = array('is', $post_id, $tags[0]);
    foreach($tags as $tag) {
      $params[2] = $tag;
      $this->prepared($sql, $params, false);
    }
    return true;
  }

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

  public function get_related_discussions($post_id) {
    $sql = 'SELECT post_title, post_id
            FROM posts 
            WHERE parent_post_id is NULL
                  AND MATCH(post_title) AGAINST((SELECT post_title FROM posts WHERE post_id=?))';
    $params = array('i', $post_id);
    $result = $this->prepared($sql, $params);
    return $result;
  } // get_related_discussions

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

    $sql = 'INSERT INTO attachments(attachment_name, attachment_size) VALUES (?, ?)';
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

  public function insert_user($username, $email, $secret, $dob) {
    $sql = "INSERT INTO users(user_email, user_name, user_secret, date_of_birth) VALUES(?, ?, ?, ?)";
    $params = array('ssss', $email, $username, $secret, $dob);
    return $this->prepared($sql, $params, false);
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



  public function check_user_data(& $ud_city, & $ud_timezone, & $ud_country_code) {
    $sql = "SELECT city_id FROM cities WHERE city_name=?";
    $params = array('s', $ud_city);
    $result = $this->prepared($sql, $params);
    if (!$result) {
      $ud_city = null;
    }
    $sql = "SELECT timezone_id FROM timezones WHERE timezone_name=?";
    $params = array('s', $ud_timezone);
    $result = $this->prepared($sql, $params);
    if (!$result) {
      $ud_timezone = null;
    }
  }



  public function get_new_session_id() {
    $user_info = get_client_info();
    $city = $user_info['region']['city'];
    $country = $user_info['country']['alpha-2'];
    $timezone = $user_info['timezone']['name'];
    echo "$city - $country - $timezone";
    $token = md5(openssl_random_pseudo_bytes(64));
    $sql = "INSERT INTO user_sessions()";
  }

  public function insert_country($cc, $cn, $co) {
    if ($cn && $cc && $co) {
      $sql = 'INSERT INTO countries (country_code, country_name, continent_code)
              VALUES (?, ?, ?)
              ON DUPLICATE KEY
              UPDATE country_code=country_code';
      $params = array('sss', $cc, $cn, $co);
      $this->prepared($sql, $params, false);
    }
  }

  public function is_username_available($un) {
    $sql = 'SELECT user_id FROM users WHERE user_name=?';
    $params = array('s', $un);
    return count($this->prepared($sql, $params))===0;
  }
  public function _load($f) {
    $sql = "LOAD DATA LOCAL INFILE '/home/obi/Downloads/sp/$f'
            INTO TABLE ip2location_db11
            FIELDS TERMINATED BY ','
            ENCLOSED BY '\"'
            LINES TERMINATED BY '\\r\\n'
            IGNORE 0 LINES";
    return $this->mysqli->query($sql);
  }

  public function update_post_view($p, $u, $cc, $t, $ua, $ip) {
    $sql = 'INSERT INTO post_views
              VALUES (?,
                      ?,
                      CURDATE(),
                      ?,
                      IFNULL(?, 1),
                      (SELECT timezone_id FROM timezones WHERE timezone_name=?),
                      ?,
                      ?)
            ON DUPLICATE KEY
              UPDATE view_count=view_count+1';
    $params = array('iisisis', $p, 1, $cc, $u, $t, $ip, $ua);
    $this->prepared($sql, $params, false);
  } // update_post_view
} // class: data_access
?>
