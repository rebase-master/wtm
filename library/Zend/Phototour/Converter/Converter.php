<?php

//This is meant to be used in a stand-alone script
//Provides utilities for maintenance and moving the database

class Phototour_Converter_Converter
{
    private $host;
    private $user;
    private $pass;
    private $source;
    private $dest;

    public function connectToDb($host, $user, $pass, $db)
    {
        mysql_connect($host, $user, $pass);
        mysql_select_db($db);
    }

    //Change phototour_v2 to a new database
    public function modifyDatabase()
    {
        /*Delete tables
         activities_bck,
         flickr_places
         flickr_shapes,
         stream_uploads,
         stream_views
        */

        mysql_query("DROP TABLE activities_bck");
        mysql_query("DROP TABLE flickr_places");
        mysql_query("DROP TABLE flickr_shapes");
        mysql_query("DROP TABLE stream_uploads");
        mysql_query("DROP TABLE stream_views");
        mysql_query("DROP TABLE stream_likes");

        echo "Dropped tables. \n";

        echo "Modifying activities table. \n";

        $sql = "ALTER TABLE activities
                ADD COLUMN created_at timestamp null,
                CHANGE updated updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";

        mysql_query($sql);

        echo "Modifying environments/ \n";

        $sql = "ALTER TABLE environments
                ADD COLUMN path text null,
                ADD COLUMN is_multires_created tinyint(1) null DEFAULT 0,
                ADD COLUMN tiles_url text null";

        mysql_query($sql);

        echo "Modifying index tables. \n";
        $index_tables = array(
            'index_environment_id', 'index_region_name',
            'index_location', 'index_type_id', 'index_user_id'
        );

        foreach ($index_tables as $t) {
            $sql = "ALTER TABLE " . $t . "
                ADD COLUMN updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                CHANGE created_at created_at TIMESTAMP NULL";
            mysql_query($sql);

            mysql_query("TRUNCATE TABLE " . $t);
        }

        echo "Removing activities data";

        mysql_query("TRUNCATE TABLE activities. \n");

        echo "Removing stream_temp data";
        mysql_query("TRUNCATE TABLE stream_temp");
        mysql_query("TRUNCATE TABLE stream_update");

        echo "Rename admin and adminweek. \n";
        mysql_query("ALTER TABLE admin RENAME to stats_daily");
        mysql_query("ALTER TABLE adminweek RENAME to stats_weekly");

        echo "Re-creating timer table. \n";
        mysql_query("DROP TABLE timer");
        mysql_query("CREATE TABLE IF NOT EXISTS `timer` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `pid` int(11) NOT NULL,
                      `name` varchar(255) NOT NULL,
                      `date` datetime NOT NULL,
                      `start` varchar(255) NOT NULL,
                      `stop` varchar(255) NOT NULL,
                      `mysql_times` float(12,12) NOT NULL,
                      `mysql_queries` int(5) NOT NULL,
                      `start_memory` bigint(50) NOT NULL,
                      `stop_memory` bigint(50) NOT NULL,
                      `start_cpu` float(12,8) NOT NULL,
                      `stop_cpu` float(12,8) NOT NULL,
                      `priority` int(11) NOT NULL,
                      PRIMARY KEY (`id`),
                      KEY `date` (`date`),
                      KEY `name` (`name`),
                      KEY `pid` (`pid`),
                      KEY `priority` (`priority`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
                ");
    }

    public function createRatingsTable()
    {
        mysql_query("CREATE TABLE IF NOT EXISTS `environments_ratings` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `env_id` int(11) NOT NULL,
                      `user_id` int(11) NOT NULL,
                      `fov` float(20,8) NOT NULL,
                      `width` float(12,8) NOT NULL,
                      `likes` float(12,8) NOT NULL,
                      `views` float(12,8) NOT NULL,
                      `comments` float(12,8) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
        ");
    }

    //Change URL inside activiies
    public function convertActivities($orig_url, $new_url)
    {
        $sql = "SELECT * FROM activities ORDER BY added_id ASC";
        $result = mysql_query($sql) or die("Could not execute query $sql");

        while ($activity = mysql_fetch_assoc($result)) {
            $body = $activity['body'];
            $inBody = gzinflate($body);
            $a = json_decode($inBody, true);
            $profile_url = $a['user']['profile_url'];

            $new_profile_url = str_replace($orig_url, $new_url, $profile_url);
            $newActivity = $a;
            $newActivity['user']['profile_url'] = $new_profile_url;

            $newData = json_encode($newActivity);
            $newBody = gzdeflate($newData);
            $newBody = mysql_real_escape_string($newBody);

            $added_id = $activity['added_id'];

            $update_sql = "UPDATE activities SET body = '$newBody' WHERE (added_id = $added_id)";
            mysql_query($update_sql) or die("Could not execute " . $update_sql);

            print $added_id . " " . $new_profile_url . "\n";
        }
    }

    //Add image_url for environments inside activities
    public function addImageUrlInsideActivities()
    {

    }

    //Change URL inside comments
    public function convertComments($orig_url, $new_url)
    {
        $sql = "SELECT * FROM comment_items ORDER BY added_id";
        $result = mysql_query($sql) or die("Could not execute query $sql");

        while ($comment = mysql_fetch_assoc($result)) {
            $body = $comment['body'];
            $inBody = gzinflate($body);
            $c = json_decode($inBody, true);

            $profile_url = $c['profile_url'];

            $new_profile_url = str_replace($orig_url, $new_url, $profile_url);

            $newComment = $c;
            $newComment['profile_url'] = $new_profile_url;

            $newData = json_encode($newComment);
            $newBody = gzdeflate($newData);
            $newBody = mysql_real_escape_string($newBody);

            $added_id = $comment['added_id'];

            $update_sql = "UPDATE comment_items SET body = '$newBody' WHERE (added_id = $added_id)";
            mysql_query($update_sql) or die("Could not execute " . $update_sql);

            print $added_id . " " . $new_profile_url . "\n";
        }
    }

    //Change URL inside environments
    public function convertEnvironments($orig_url, $new_url)
    {
        $sql = "SELECT id, image_url, thumb_url FROM environments";
        $result = mysql_query($sql) or die("Could not execute query $sql");

        while ($e = mysql_fetch_assoc($result)) {
            $new_image_url = str_replace($orig_url, $new_url, $e['image_url']);
            $new_thumb_url = str_replace($orig_url, $new_url, $e['thumb_url']);

            $environment_id = $e['id'];

            $update_sql = "UPDATE environments SET image_url = '$new_image_url', thumb_url = '$new_thumb_url' WHERE (id = $environment_id)";
            mysql_query($update_sql) or die("Could not execute query $update_sql");

            print $environment_id . " " . $new_image_url . " " . $new_thumb_url . "\n";
        }
    }

    //Change URL inside mapsource_phototour
    public function convertMapsourcePhototour($orig_base_owner_url, $new_base_owner_url, $orig_url, $new_url)
    {
        $sql = "SELECT photo_id, owner_url, original_url, photo_file_url, photo_url, thumbnail_url FROM mapsource_phototour";
        $result = mysql_query($sql) or die("Could not execute query $sql");

        while ($e = mysql_fetch_assoc($result)) {
            $new_owner_url = str_replace($orig_base_owner_url, $new_base_owner_url, $e['owner_url']);
            $new_original_url = str_replace($orig_url, $new_url, $e['original_url']);
            $new_photo_file_url = str_replace($orig_url, $new_url, $e['photo_file_url']);
            $new_photo_url = str_replace($orig_url, $new_url, $e['photo_url']);
            $new_thumbnail_url = str_replace($orig_url, $new_url, $e['thumbnail_url']);

            $photo_id = $e['photo_id'];

            $update_sql = "UPDATE mapsource_phototour SET
                        owner_url = '$new_owner_url',
                        original_url = '$new_original_url',
                        photo_file_url = '$new_photo_file_url',
                        photo_url = '$new_photo_url',
                        thumbnail_url = '$new_thumbnail_url'
                        WHERE (photo_id = $photo_id)";

            mysql_query($update_sql) or die("Could not execute query $update_sql");

            print $photo_id . " " . $new_photo_url . "\n";
        }
    }

    //Copy all phototour files from one directory to another
    public function copyFiles($source_base_dir, $dest_base_dir)
    {
        $dirs = array(
            "env_thumbs/",
            "photos/",
            "photos_resize/",
            "thumbs/",
            "user/",
            "mask_images/",
            "floorplan/"
        );

        foreach ($dirs as $dir) {
            $source_dir = $source_base_dir . $dir;
            $dest_dir = $dest_base_dir . $dir;

            print "Copying " . $source_dir . " to " . $dest_dir . "\n";

            Phototour_Files::rcopy($source_dir, $dest_dir);
        }
    }

    //Remove all salts from thumb urls
    /*
     * Current original file. env_thumbs/$env_id/md5($environment.$salt)_width_height.jpg
     */
    public function renameEnvironmentImages($source_dir)
    {
        print $source_dir . "\n";
        if (is_dir($source_dir)) {
            $files = scandir($source_dir);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $dir = $source_dir . "/" . $file . "/";
                    //This is a directory
                    $env_files = scandir($dir);
                    print $dir . "\n";
                    foreach ($env_files as $env_file) {
                        if ($env_file != "." && $env_file != "..") {
                            print $env_file . "\n";
                            $parts = explode("_", $env_file);
                            $new_name = $parts[1] . "_" . $parts[2];
                            print $new_name . "\n";

                            $source = $dir . "/" . $env_file;
                            $dest = $dir . "/" . $new_name;

                            rename($source, $dest);
                        }
                    }
                }
            }

        }
    }

    //Add index_type_id for photographs
    public function addPhotoTypeIds()
    {
        //Select all stream inside activities
        //Unpack and check environment_id and type
        //If the activity_id and type are already present, don't add it
        //Add it otherwise
        $types = array(
            'view' => 1,
            'upload' => 2,
            'like' => 3,
            'photo' => 4,
            'panorama' => 5
        );

        $sql = "SELECT * FROM activities ORDER BY added_id ASC";
        $result = mysql_query($sql) or die("Could not execute query $sql");

        while ($activity = mysql_fetch_assoc($result)) {
            $body = $activity['body'];
            $inBody = gzinflate($body);
            $a = json_decode($inBody, true);

            if (empty($a['environments'][0])) {
                continue;
            }

            $environment_id = $a['environments'][0]['id'];

            $sql_environment = "SELECT photo_type FROM environments WHERE id = $environment_id";
            $result_environment = mysql_query($sql_environment) or die("Could not execute query " . $sql_environment);

            list($photo_type) = mysql_fetch_array($result_environment);

            if (empty($photo_type)) {
                continue;
            }

            $type_id = $types[$photo_type];

            $activity_id = mysql_real_escape_string($activity['id']);

            $sql_type_check = "SELECT * FROM index_type_id WHERE (activity_id = '$activity_id' AND type_id = $type_id)";
            $result_type_check = mysql_query($sql_type_check) or die("Could not execute query $sql_type_check");

            $created_at = $activity['updated'];
            $sql_insert = "INSERT INTO index_type_id (activity_id, type_id, created_at)
                           VALUES('$activity_id', $type_id, '$created_at')";
            $result_insert = mysql_query($sql_insert);

            echo $environment_id . " = " . $photo_type . "\n";
        }
    }

    public function getUserActionEnvs($user_id, $onlyUploaded)
    {
        $envs = array();
        if (!$onlyUploaded) {
            //Get environments commented upon
            $sql_comments = "SELECT DISTINCT element_id, comment_items.updated FROM comment_items INNER JOIN comment_threads
                    ON comment_items.thread_id=comment_threads.id
                    WHERE comment_items.user_id=$user_id";

            $result_comments = mysql_query($sql_comments);

            while (list($id, $t) = mysql_fetch_array($result_comments)) {
                $parts = explode("_", $id);
                $envs[] = array(
                    'id' => (int)($parts[2]),
                    't' => $t,
                    'type' => "comment"
                );
            }

            $sql_faved = "SELECT environment_id, created_at FROM votes WHERE user_id=$user_id";
            $result_faved = mysql_query($sql_faved);

            while (list($id, $t) = mysql_fetch_array($result_faved)) {
                $envs[] = array(
                    'id' => (int)($id),
                    't' => $t,
                    'type' => "faved"
                );
            }

        }

        $sql_uploaded = "SELECT id, created_at FROM environments WHERE user_id=$user_id";
        $result_uploaded = mysql_query($sql_uploaded);

        while (list($id, $t) = mysql_fetch_array($result_uploaded)) {
            $envs[] = array(
                'id' => (int)($id),
                't' => $t,
                'type' => "uploaded"
            );
        }

        return $envs;
    }

    public function addEnvsToTemp() {
        $sql = "SELECT * FROM environments WHERE deleted = 0 AND photo_id IS NOT NULL";
        $result = mysql_query($sql);

        while($e = mysql_fetch_assoc($result)) {
            $user_id = $e['user_id'];
            $env_id = $e['id'];
            $created_at = $e['created_at'];
            
            $sql_insert = "INSERT INTO stream_temp (type, user_id, environment_id, created_at)
                                                  VALUES ('upload', $user_id, $env_id, '$created_at')";
            $result_temp = mysql_query($sql_insert);
            echo $user_id . " " . $env_id . " " . $created_at . "\n";
        }
    }

    public function addRemainingUserIdIndexes()
    {
        $sql = "SELECT id,username FROM users WHERE deleted = 0";
        $result = mysql_query($sql) or die("Could not execute query $sql");

        while (list($user_id, $username) = mysql_fetch_array($result)) {
            echo $user_id . " " . $username . "\n";

            $eAr = $this->getUserActionEnvs($user_id, false);

            //Get all activity IDs together

            $envIds = array();
            foreach($eAr as $e) {
                $envIds[] = $e['id'];
            }

            if(empty($envIds)) {
                continue;
            }

            $envIdSt = join(",", $envIds);

            $sql_env_index = "SELECT environment_id, activity_id, created_at FROM index_environment_id WHERE (environment_id IN ($envIdSt))";
            echo $sql_env_index . "\n";
            $result_env_index = mysql_query($sql_env_index);

            while (list($environment_id, $activity_id, $created_at) = mysql_fetch_array($result_env_index)) {

                $activity_id = mysql_real_escape_string($activity_id);
                $sql_user_index = "SELECT * FROM index_user_id WHERE user_id = $user_id AND activity_id = '$activity_id'";
                $result_user_index = mysql_query($sql_user_index);
                $r = mysql_fetch_row($result_user_index);

                if (empty($r)) {
                    $sql_insert = "INSERT INTO index_user_id (user_id, activity_id, created_at, updated_at)
                                    VALUES($user_id, '$activity_id', '$created_at', '$created_at')";
                    $result_insert = mysql_query($sql_insert) or die("\n\nFailed inserting $sql_insert " . mysql_error() . "\n\n\n\n");
                    echo "Inserted " . $environment_id . " " . $created_at . "\n";
                }
                else {
                    echo "Index already present. " . $environment_id;
                }
            }
        }
    }

    public function removeBadActivities()
    {
        $sql = "SELECT * FROM activities ORDER BY added_id ASC";
        $result = mysql_query($sql) or die("Could not execute query $sql");

        while ($activity = mysql_fetch_assoc($result)) {
            $body = $activity['body'];
            $inBody = gzinflate($body);
            $a = json_decode($inBody, true);

            $is_bad = false;

            if (empty($a['environments'])) {
                $is_bad = true;
            }
            else if (empty($a['environments'][0])) {
                $is_bad = true;
            }

            if ($is_bad) {
                echo $activity['added_id'] . " is bad. \n";
                $activity_id = mysql_real_escape_string($activity['id']);
                $sql_delete = "DELETE FROM activities WHERE (id = '$activity_id')";
                $result_delete = mysql_query($sql_delete) or die("Could not execute query $sql_delete");
            }
        }
    }

    public function updatePathsFromImageUrl($base_url)
    {
        $sql = "SELECT id, image_url FROM environments ORDER BY id ASC";
        $result = mysql_query($sql) or die("Could not execute query $sql");

        while (list($id, $image_url) = mysql_fetch_array($result)) {
            $path = str_replace($base_url, "", $image_url);
            $sql_update = "UPDATE environments SET path = '$path' WHERE (id = $id)";
            $result_update = mysql_query($sql_update);
            echo $id . " " . $path . "\n";
        }
    }

    public function generateThumbsAndTiles()
    {
        $exchange = 'ex_teliportme';
        $queue = 'environments';

        $conn = new AMQPConnection();
        $conn->connect();

        // Declare a new exchange
        $ex = new AMQPExchange($conn);
        $ex->declare($exchange, AMQP_EX_TYPE_DIRECT);

        // Create a new queue
        $q = new AMQPQueue($conn);
        $q->declare($queue, AMQP_DURABLE);
        // Bind it on the exchange to routing.key

        $routingKey = 'api.environments.multires.create';

        $ex->bind($queue, $routingKey);

        $sql = "SELECT id, photo_id, path, photo_type FROM environments WHERE is_multires_created = 0 AND photo_id IS NOT NULL ORDER BY id DESC";
        //        $sql = "SELECT id, photo_id, path, photo_type FROM environments WHERE photo_type = 'photo' ORDER BY id ASC";
        $result = mysql_query($sql) or die("Could not execute query. $sql");


        while (list($environment_id, $photo_id, $path, $type) = mysql_fetch_array($result)) {
            $sql_fov = "SELECT fov FROM mapsource_phototour WHERE photo_id = '$photo_id'";
            $result_fov = mysql_query($sql_fov);
            list($fov) = mysql_fetch_array($result_fov);

            $params = array(
                'environment_id' => $environment_id,
                'path' => $path,
                'type' => $type,
                'fov' => $fov
            );
            $msg_body = json_encode($params);
            // Publish a message to the exchange with a routing key
            $ex->publish($msg_body, $routingKey);
            echo $msg_body . "\n";
        }

        $conn->disconnect();
    }

    public function createStream($stream_create_url)
    {
        //Generate activities using curl
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $stream_create_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);

        // grab URL and pass it to the browser
        curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close($ch);
    }

}