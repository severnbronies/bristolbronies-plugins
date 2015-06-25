<?php
/*
Plugin Name: Bristol Bronies social feed - Twitter
Plugin URI: http://bristolbronies.co.uk/
Description: Pull down our most recent tweets. 
Author: Kimberly Grey
Author URI: http://greysadventures.com/
*/

require_once "libs/TwitterAPIExchange.class.php";

function bb_tweet_feed($account, $limit = 5) { 
	$settings = array(
		"oauth_access_token" => TWITTER_OAUTH_TOKEN,
		"oauth_access_token_secret" => TWITTER_OAUTH_SECRET,
		"consumer_key" => TWITTER_CONSUMER_KEY,
		"consumer_secret" => TWITTER_CONSUMER_SECRET
	);
	$url = "https://api.twitter.com/1.1/search/tweets.json";
	$options = "?result_type=recent&q=from%3A" . $account;
	if($limit > 0) {
		$options .= "&count=" . $limit;
	}
	$method = "GET";

	$twitter = new TwitterAPIExchange($settings);
	$data = json_decode($twitter->setGetfield($options)->buildOauth($url, $method)->performRequest());

	$output = '<div class="social-feed">';
	foreach($data->statuses as $status) {
		$output .= '<div class="social-feed__item">'
		         . '<a class="social-feed__source social-feed__source--twitter" href="http://twitter.com/' . $status->user->screen_name . '">' . $status->user->screen_name . '</a>'
		         . '<div class="social-feed__body">'
		         . '<div class="content social-feed__content">' . $status->text . '</div>'
		         . '<time class="social-feed__timestamp" datetime="' . date("c", strtotime($status->created_at)) . '">' . date("Y-m-d H:i:s", strtotime($status->created_at)) . '</time>'
		         . '</div>'
		         . '</div>';
	}
	$output .= '</div>';
	echo $output;
}