<?php

use \j3j5\TwitterApio;

class Search {

	private $max_items;

	public function __construct() {
		$this->max_items = 50;
	}

	public function get_max_items() {
		return $this->max_items;
	}

	public function set_max_items($max_items) {
		$this->max_items = $max_items;
	}

	/**		TWITTER		**/

	/**
	 * Search on Youtube's feed api the plus the given query
	 *
	 * @param String $query The query to search for
	 *
	 * @access private
	 *
	 * @return String
	 */
	public function twitter($query, $count = FALSE) {
		// YAStickers token with Boooobs.in api
		$tokens = array(
			'consumer_key'		=> 'DMbcVoRTj4oVQYUGf5T7Ks7Fp',
			'consumer_secret'	=> 'fj5DYOKR1OWNf5ZIdIQpTNbtJYYb6b5SxacqH82zLCjxrjD4Pc',
			'token' 			=> '445461666-Tobj5JRgRBzJ4QNJhHyfFPZtf3Nuwya8agfimTpF',
			'secret'			=> '1iBAsJTTSfzWwE8XV0VPJfs8rEuXQFVzsjHv4tp17xVNf'
		);
		$query = 'boobs ' . $query . ' filter:images -RT';
		if(!is_numeric($count)) {
			$count = $this->max_items;
		}
		$options = array(
			'q' => $query,
			'result_type ' => 'mixed',	// mixed, recent, popular
			'count' => $count + 5,
			'include_entities' => '1',
		);
		$api = new TwitterApio($tokens, array('json_decode' => 'object'));
		$tweets = array();
		foreach($api->get_timeline('/search/tweets', $options) AS $page) {
			if(is_array($page) && !empty($page)) {
				$tweets = array_merge($tweets, $page);
			}

			// Stop once we've retrieved enough
			if(count($tweets) >= $this->max_items) {
				break;
			}
		}
		$tweets = array_slice($tweets, 0, $count);
		return $this->extract_twitter_urls($tweets);
	}

	private function extract_twitter_urls($response) {
		// Check the integrity of the response
		if(!is_array($response)) {
			var_dump(__LINE__);
			var_dump($response); exit;
			return array();
		}
		$images = $urls = $usernames = array();
		$amount = 0;
		// Go through the tweets
		foreach($response AS $status) {
			if(isset($status->entities->media)) {

				foreach($status->entities->media as $pic) {
					if(isset($pic->media_url)) {
						$urls[] = "https://twitter.com/" . $status->user->screen_name . "/statuses/" . $status->id;
						$usernames[] = '@' . $status->user->screen_name;
						$images[] = $pic->media_url;
						$amount++;
					} else {
						var_dump(__LINE__);
						var_dump($pic); exit;
					}
				}
			} /*else {
			// Videos go here, TODO: Chek how to embed them.
				var_dump($status); exit;
				foreach($status->entities->urls as $pic) {
					if(isset($pic->display_url)) {
						$images[] = 'http://' . $pic->display_url;
					} else {
						var_dump(__LINE__);
						var_dump($pic); exit;
					}
				}
			}*/
		}

		$removed_images = array();
		$this->clean_repeated_images($images, $urls, $usernames, $removed_images);

		return array('images' => $images, 'urls' => $urls, 'usernames' => $usernames, 'removed' => $removed_images);
	}

	private function clean_repeated_images(&$images, &$urls, &$usernames, &$removed_images) {
		$md5_pattern = '/content-md5: (.{24})/i';
		$md5_tags = array();
		foreach($images AS $index => $image) {
			$md5_match = array();
			if(function_exists('get_headers')) {
				$headers = get_headers($image);
				foreach($headers AS $header) {
					// Don't perform the regex on all headers, mb_strpos is much faster
					if(mb_strpos($header, 'content-md5') === FALSE) {
						continue;
					}
					if(preg_match($md5_pattern, $header, $md5_match)) {
						if(!in_array($md5_match[1], $md5_tags)) {
							$md5_tags[] = $md5_match[1];
						} else {
							$removed_images['images'][] = $images[$index];
							$removed_images['urls'][] = $urls[$index];
							$removed_images['usernames'][] = $usernames[$index];
							// The image is already there, remove it!!
							unset($images[$index]);
							unset($urls[$index]);
							unset($usernames[$index]);
						}
					}
					$md5_match = array();
				}
			} else {
			    var_dump('get_headers'); exit;
			}
		}
	}

	/**		REDDIT		**/

	public function reddit($query, $subreddit = 'boobs', $after = FALSE) {
		$query = '';
		$base_url = "http://www.reddit.com/r/$subreddit/.json";
		$iterations = 0;
		$max_iterations = 5;
		$images = array();
		$parameters = array('count' => $this->max_items);
		if($after) {
			$parameters['after'] = $after;
		}
		while(count($images) < $this->max_items) {
			$data = $this->do_curl_call($base_url, $parameters);
			// Extract URLs from the response
			if(!empty($data)) {
				$json = json_decode($data, TRUE);
				$temp = $this->extract_reddit_urls($json);
				if(!empty($temp) && is_array($temp)) {
					$images = array_merge($images, $temp);
				}
			}
			if(isset($json['data']['after']) && !empty($json['data']['after'])) {
				$parameters['after'] = $json['data']['after'];
				$images['images']['after'] = $json['data']['after'];
			} else {
// 				var_dump($json); exit;
				break;
			}
			$iterations++;
			if($iterations > $max_iterations) {
				break;
			}
		}

		if(empty($data)) {
			// Load empty results page.
			$images = array();
		}
		return $images;
	}

	private function extract_reddit_urls($json) {
		$images = $urls = $usernames = array();
		$matches = array();
		if(isset($json['data']['children'])) {
			$added = FALSE;
			$imgur_galleries_regex = "/^http[s]?:\/\/[w\.]{0,4}imgur\.com\/a\/([a-z0-9\-_#]*)(\/all\/?)?$/i";
			$imgur_pic = "/^http[s]?:\/\/[w\.]{0,4}[a-z0-9]*\.[a-z]{2,6}\/[a-z0-9\/\-_#]*\.(jp[e]?g|png|gif)/i";
			$imgur_link = "/^http[s]?:\/\/[w\.]{0,4}imgur\.com\/([a-z0-9\-_#]{1,10}|gallery\/([a-z0-9\-\_#]{1,10}))$/i";
			foreach($json['data']['children'] AS $item) {
				if(isset($item['data']['domain'])) {
					switch ($item['data']['domain']) {
						case 'imgur.com':
							// imgur.com normal links
// 							if(preg_match("/^http[s]?:\/\/imgur\.com\/([a-z0-9\-_#]{1,10})$/i", $item['data']['url'], $matches)) {
							if(preg_match($imgur_link, $item['data']['url'], $matches)) {
								$image_id = count($matches) > 2 ? $matches[2] : $matches[1];
								$images[] = 'http://i.imgur.com/' . $image_id . '.gif';	// Use .gif extension, imgur returns the same image no matter what extension you use
								$added = TRUE;
							} // imgur direct link
							elseif(preg_match($imgur_pic, $item['data']['url'])) {
								$images[] = $item['data']['url'];
								$added = TRUE;
							} // imgur galleries
							elseif(preg_match($imgur_galleries_regex, $item['data']['url'], $matches)) {
								if(isset($matches[1]) && !empty($matches[1])) {
									$url = 'https://api.imgur.com/2/album/' . $matches[1] . '.json';
									$api_result = $this->do_curl_call($url);
									$json = json_decode($api_result, TRUE);
									if(isset($json['album']['images']) && !empty($json['album']['images'])) {
										foreach($json['album']['images'] AS $image) {
											if(isset($image['links']['original'])) {
												$images[] = $image['links']['original'];
												$usernames[] = $item['data']['author'];
												$urls[] = 'http://reddit.com' . $item['data']['permalink'];
											}
										}
									}
									// The username and URL is added for the whole gallery, so don't add again
									$added = FALSE;
								}
							} else {
								var_dump(__LINE__);
								var_dump($item['data']['url']);
								exit;
							}

							break;
						case 'i.imgur.com':
							if(preg_match('/^http[s]?:\/\/i\.imgur\.com\/[a-z0-9]{1,10}\.(jp[e]?g|png|gif)\??.*$/i', $item['data']['url'])){
								$images[] = $item['data']['url'];
								$added = TRUE;
							} else {
								var_dump(__LINE__);
								var_dump($item['data']['url']); exit;
							}
							break;
						case 'vimeo.com':
							break;
						default:
							if(preg_match("/^http[s]{0,1}:\/\/[a-z0-9]*\.[a-z]{2,6}\/[a-z0-9\/\-_]*\.(jp[e]?g|png|gif)$/i", $item['data']['url'])) {
								$images[] = $item['data']['url'];
								$added = TRUE;
							}
							break;
					}
				}
				if($added) {
					$usernames[] = $item['data']['author'];
					$urls[] = 'http://reddit.com' . $item['data']['permalink'];
					$added = FALSE;
				}
				if(count($images) >= $this->max_items) {
					return array('images' => $images, 'urls' => $urls, 'usernames' => $usernames);
				}
			}
		}
		return array('images' => $images, 'urls' => $urls, 'usernames' => $usernames);
	}



	/**		TUMBLR		**/

	public function tumblr($tag = "tetas") {
		$api_key = "IRCH87ahPw5ZamQtW72G7F1oA0QO0xTQPKVdvd1TGkDScQW0bs";
		if(empty($tag)) {
			$tag = 'tetas';
		}
		$base_url = "http://api.tumblr.com/v2/tagged?tag=$tag&api_key=$api_key";

		$data = $this->do_curl_call($base_url);
		return $this->extract_tumblr_urls($data);
	}

	private function extract_tumblr_urls($response) {
		$images = $urls = $usernames = array();
		$json = json_decode($response, TRUE);
		$amount = 0;
		if(isset($json['response']) && !empty($json['response'])) {
			foreach($json['response'] AS $post) {
				if(!isset($post['photos'])) {
					continue;
				}
				foreach($post['photos'] AS $photo) {
					if(isset($photo['original_size']['url'])) {
						$amount++;
						$images[] = $photo['original_size']['url'];
						$usernames[] = $post['blog_name'];
						$urls[] = $post['post_url'];
					}
					// Use the global limit
					if($amount >= $this->max_items) {
						return array('images' => $images, 'urls' => $urls, 'usernames' => $usernames);
					}
				}
			}
		}
		return array('images' => $images, 'urls' => $urls, 'usernames' => $usernames);
	}


	/**		FFFFOUND		**/

	public function on_ffffound($user) {
		$url = "http://ffffound.com/home/$user/found/feed";
		$xml = $this->do_curl_call($url);
		return $this->extract_ffffound_urls($xml);
	}

	private function extract_ffffound_urls($xml) {
		try {
			$feed = new SimpleXMLElement($xml);
		} catch(Exception $e) {
			// User proper error handling
			var_dump($e->getMessage());
			return FALSE;
		}
		$matches = $images = $urls = $usernames = array();
		$added = FALSE;
		$amount = 0;
		foreach($feed->channel->item AS $item) {
			$desc = (string)$item->description;
			if(preg_match("/http[s]?:\/\/img\.ffffound\.com\/static-data\/assets\/6\/\S*\.(gif|jp[e]?g|png)/i", $desc, $matches)) {
				$images[] = $matches[0];
				$amount++;
				$added = TRUE;
			}
			if($added) {
				if(preg_match('/<href="([a-z\/\.:0-9\?=]*)">/i', $desc, $matches)) {
					$urls[] = $matches[1];
				}
				if(isset($item->author)) {
					$usernames[] = (string)$item->author;
				}
				$added = FALSE;
			}
			// Use the global limit
			if($amount >= $this->max_items) {
				return array('images' => $images, 'urls' => $urls, 'usernames' => $usernames);
			}
		}
		return array('images' => $images, 'urls' => $urls, 'usernames' => $usernames);
	}


	/**		INSTAGRAM		**/


	private function _search_on_instagram($query) {
		$query = 'snow' . $query;
		$url = "https://api.instagram.com/v1/tags/$query/media/recent?access_token=1411766014.f59def8.e27408fec8cb4fefafd10b90439b3782";
		$json = $this->do_curl_call($url);
		return $this->_extract_instagram_urls($json);
	}

	private function _extract_instagram_urls($response) {
		var_dump($response); exit;
	}


	private function do_curl_call($url, $parameters = array()) {
		// Add parameters
		$first_param = TRUE;
		foreach($parameters AS $name => $value) {
			if($first_param) {
				$url .= "?$name=$value";
				$first_param = FALSE;
			} else {
				$url .= "&$name=$value";
			}
		}
		// Do the cURL call
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}

/* End of file Search.php */
/* Location: ./application/libraries/Search.php */
