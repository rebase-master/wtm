<?php

class Phototour_User
{
    protected $_id;
    protected $_username;
    protected $_email;
    protected $_privilege;
    protected $_created_at;
    protected $_num_followers;
    protected $_num_following;
    protected $_photos_viewed;
    protected $_photos_liked;
    protected $_photos_disliked;
    protected $_photos_uploaded;
    protected $_fb_uid;
    protected $_fb_first_name;
    protected $_fb_last_name;
    protected $_fb_pic_big;
    protected $_fb_pic_square;
    protected $_fb_profile_url;
    protected $_flickr_url;
    protected $_twitter_url;
    protected $_profile_pic_url;

    protected $_deleted;

    protected $_access_token;
    protected $_refresh_token;
    protected $_expires_in;
    protected $_is_fb_user;
    protected $_display_name;

    protected $_friends;

    protected $_profile_pic_thumb;
    protected $_profile_pic_medium;
    protected $_profile_pic_large;

    protected $_guid;

    function __construct($params)
    {
        $this->_id = isset($params['id']) ? $params['id'] : NULL;
        $this->_username = isset($params['username']) ? $params['username'] : NULL;
        $this->_email = isset($params['email']) ? $params['email'] : NULL;
        $this->_privilege = isset($params['privilege']) ? $params['privilege'] : NULL;
        $this->_created_at = isset($params['created_at']) ? $params['created_at'] : NULL;
        $this->_num_followers = isset($params['num_followers']) ? $params['num_followers'] : NULL;
        $this->_num_following = isset($params['num_following']) ? $params['num_following'] : NULL;
        $this->_photos_uploaded = isset($params['photos_uploaded']) ? $params['photos_uploaded'] : NULL;
        $this->_photos_viewed = isset($params['photos_viewed']) ? $params['photos_viewed'] : NULL;
        $this->_photos_liked = isset($params['photos_liked']) ? $params['photos_liked'] : NULL;
        $this->_photos_disliked = isset($params['photos_disliked']) ? $params['photos_disliked'] : NULL;
        $this->_fb_uid = isset($params['fb_uid']) ? $params['fb_uid'] : NULL;
        $this->_fb_first_name = isset($params['fb_first_name']) ? $params['fb_first_name'] : NULL;
        $this->_fb_last_name = isset($params['fb_last_name']) ? $params['fb_last_name'] : NULL;
        $this->_fb_pic_big = isset($params['fb_pic_big']) ? $params['fb_pic_big'] : NULL;
        $this->_fb_profile_url = isset($params['fb_profile_url']) ? $params['fb_profile_url'] : NULL;
        $this->_flickr_url = isset($params['flickr_url']) ? $params['flickr_url'] : NULL;
        $this->_twitter_url = isset($params['twitter_url']) ? $params['twitter_url'] : NULL;
        $this->_profile_pic_url = isset($params['profile_pic_url']) ? $params['profile_pic_url'] : NULL;
        $this->_deleted = isset($params['deleted']) ? $params['deleted'] : NULL;
        $this->_access_token = isset($params['access_token']) ? $params['access_token'] : NULL;
        $this->_refresh_token = isset($params['refresh_token']) ? $params['refresh_token'] : NULL;
        $this->_expires_in = isset($params['expires_in']) ? $params['expires_in'] : NULL;
        $this->_is_fb_user = isset($params['is_fb_user']) ? $params['is_fb_user'] : NULL;
        $this->_display_name = isset($params['display_name']) ? $params['display_name'] : NULL;

        if (empty($params['guid'])) {
            $guids = new Api_Model_Guids();
            $this->_guid = $guids->generateGuid();
        }
        else
        {
            $this->_guid = $params['guid'];
        }

        if ($this->_profile_pic_url == NULL) {
            if ($this->_fb_uid == NULL) {
                $config = Zend_Registry::get("config");
                $this->_profile_pic_thumb = $config->urls->blankProfilePicThumbUrl;
                $this->_profile_pic_medium = $config->urls->blankProfilePicMediumUrl;
                $this->_profile_pic_large = $config->urls->blankProfilePicLargeUrl;
            }
            else {
                $this->_profile_pic_thumb = "http://graph.facebook.com/" . $this->_fb_uid . "/picture";
                $this->_profile_pic_medium = "http://graph.facebook.com/" . $this->_fb_uid . "/picture&type=large";
                $this->_profile_pic_large = "http://graph.facebook.com/" . $this->_fb_uid . "/picture&type=large";
            }
        }

    }

    public function getId()
    {
        return $this->_id;
    }

    public function getPrivilege()
    {
        return $this->_privilege;
    }

    public function getProfilePicThumb()
    {
        return $this->_profile_pic_thumb;

    }

    public function getProfilePicMedium()
    {
        return $this->_profile_pic_medium;
    }

    public function getProfilePicUrl()
    {
        return $this->_profile_pic_url;
    }

    public function getProfilePicLarge()
    {
        return $this->_profile_pic_large;
    }

    public function getDisplayName()
    {
        if ($this->_fb_first_name != NULL) {
            return $this->_fb_first_name . " " . $this->_fb_last_name;
        }
        else {
            return $this->_username;
        }
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function getFbFirstName()
    {
        return $this->_fb_first_name;
    }

    public function getFbLastName()
    {
        return $this->_fb_last_name;
    }

    public function getFirstName()
    {
        return $this->_fb_first_name;
    }

    public function getLastName()
    {
        return $this->_fb_last_name;
    }

    public function getFbUid()
    {
        return $this->_fb_uid;
    }

    public function getEmail()
    {
        return $this->_email;
    }


    public function getPhotosUploadedCount()
    {
        return $this->_photos_uploaded;
    }

    public function getPhotosViewedCount()
    {
        return $this->_photos_viewed;
    }

    public function getFollowerCount()
    {
        if ($this->_num_followers == NULL) {
            $this->_num_followers = 0;
        }
        return $this->_num_followers;
    }

    public function getLikeCount()
    {
        if ($this->_photos_liked == NULL) {
            $this->_photos_liked = 0;
        }
        return $this->_photos_liked;
    }


    public function getAccessToken()
    {
        return $this->_access_token;
    }

    public function getFollowingCount()
    {
        if ($this->_num_following == NULL) {
            $this->_num_following = 0;
        }
        return $this->_num_following;
    }

    public function getTwitterUrl()
    {
        return $this->_twitter_url;
    }

    public function getFlickrUrl()
    {
        return $this->_flickr_url;
    }

    public function update($params)
    {
        $this->_id = isset($params['id']) ? $params['id'] : $this->_id;
        $this->_username = isset($params['username']) ? $params['username'] : $this->_username;
        $this->_email = isset($params['email']) ? $params['email'] : $this->_email;
        $this->_privilege = isset($params['privilege']) ? $params['privilege'] : $this->_privilege;
        $this->_created_at = isset($params['created_at']) ? $params['created_at'] : $this->_created_at;
        $this->_num_followers = isset($params['num_followers']) ? $params['num_followers'] : $this->_num_followers;
        $this->_num_following = isset($params['num_following']) ? $params['num_following'] : $this->_num_following;
        $this->_photos_uploaded = isset($params['photos_uploaded']) ? $params['photos_uploaded'] : $this->_photos_uploaded;
        $this->_photos_viewed = isset($params['photos_viewed']) ? $params['photos_viewed'] : $this->_photos_viewed;
        $this->_photos_liked = isset($params['photos_liked']) ? $params['photos_liked'] : $this->_photos_liked;
        $this->_photos_disliked = isset($params['photos_disliked']) ? $params['photos_disliked'] : $this->_photos_disliked;
        $this->_fb_uid = isset($params['fb_uid']) ? $params['fb_uid'] : $this->_fb_uid;
        $this->_fb_first_name = isset($params['fb_first_name']) ? $params['fb_first_name'] : $this->_fb_first_name;
        $this->_fb_last_name = isset($params['fb_last_name']) ? $params['fb_last_name'] : $this->_fb_last_name;
        $this->_fb_pic_big = isset($params['fb_pic_big']) ? $params['fb_pic_big'] : $this->_fb_pic_big;
        $this->_fb_profile_url = isset($params['fb_profile_url']) ? $params['fb_profile_url'] : $this->_fb_profile_url;
        $this->_flickr_url = isset($params['flickr_url']) ? $params['flickr_url'] : $this->_flickr_url;
        $this->_twitter_url = isset($params['twitter_url']) ? $params['twitter_url'] : $this->_twitter_url;
        $this->_profile_pic_url = isset($params['profile_pic_url']) ? $params['profile_pic_url'] : $this->_profile_pic_url;
        $this->_deleted = isset($params['deleted']) ? $params['deleted'] : $this->_deleted;
        $this->_access_token = isset($params['access_token']) ? $params['access_token'] : $this->_access_token;
        $this->_refresh_token = isset($params['refresh_token']) ? $params['refresh_token'] : $this->_refresh_token;
        $this->_expires_in = isset($params['expires_in']) ? $params['expires_in'] : $this->_expires_in;
        $this->_is_fb_user = isset($params['is_fb_user']) ? $params['is_fb_user'] : $this->_is_fb_user;
        $this->_display_name = isset($params['display_name']) ? $params['display_name'] : $this->_display_name;

        $this->_profile_pic_thumb = isset($params['profile_pic_thumb']) ? $params['profile_pic_thumb'] : $this->_profile_pic_thumb;
        $this->_profile_pic_medium = isset($params['profile_pic_medium']) ? $params['profile_pic_medium'] : $this->_profile_pic_medium;
        $this->_profile_pic_large = isset($params['profile_pic_large']) ? $params['profile_pic_large'] : $this->_profile_pic_large;


    }

    public function getGuid()
    {
        return $this->_guid;
    }

}