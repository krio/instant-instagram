<?php

class InstaRio
{
    private $html;
    private $client_id;
    private $user_id;
    private $plugin_dir;
    private $num_images;

    public function __construct($client_id, $user_id, $num_images)
    {
        $this->client_id = trim($client_id);
        $this->user_id = trim($user_id);
        $this->num_images = $num_images;
        $this->plugin_dir = plugin_dir_path(__FILE__);

        $this->html = $this->imagesToHTML($this->fetchRecent());
    }

    private function imagesToHTML($images)
    {
        $html = '<div class="insta_rio_wrap">';
        foreach ($images as $insta_entry_k => $insta_entry) {
            $img_classes = '';
            if ($insta_entry_k === 0) {
                $img_classes .= 'insta_rio_first';
                $url = $insta_entry['images']['standard_resolution']['url'];
            }
            else {
                $img_classes .= 'insta_rio_small';
                $url = $insta_entry['images']['thumbnail']['url'];
            }
            $html .= '<a title="' . $insta_entry['caption']['text'] . '" class="insta_rio_link noHover" href="' . $insta_entry['link'] . '"><img class="' . $img_classes . '" src="' . $url . '"/></a>';
        }
        $html .= '</div>';
        return $html;
    }

    private function fetchRecent()
    {
        $fetch_new = false;
        $insta_recent = null;
        $cache_file_path = $this->plugin_dir . 'instagram_recent_fetched.json';

        if (!file_exists($cache_file_path)) {
            $fetch_new = true;
        }
        else {
            $response = file_get_contents($cache_file_path);
            if ($response) {
                $insta_recent = json_decode($response, true);
                if (!$insta_recent || $insta_recent['fetched_at'] < strtotime('-20 minutes') || !$insta_recent['response']) {
                    $fetch_new = true;
                }
            }
            else {
                $fetch_new = true;
            }
        }

        if ($fetch_new) {
            $insta_params = ['client_id' => $this->client_id, 'count' => $this->num_images];
            $insta_endpoint = 'https://api.instagram.com/v1/users/' . urlencode($this->user_id) . '/media/recent/?';
            foreach ($insta_params as $ip_k => $ip_v) {
                $insta_endpoint .= '&' . urlencode($ip_k) . '=' . urlencode($ip_v);
            }
            $insta_recent = array(
                'fetched_at' => strtotime('now'),
                'response' => json_decode(file_get_contents($insta_endpoint), true)
            );
            file_put_contents($cache_file_path, json_encode($insta_recent));
        }

        $images = array();
        if ($insta_recent && $insta_recent['response']) {
            foreach ($insta_recent['response']['data'] as $insta_entry) {
                $images[] = $insta_entry;
            }
        }
        return $images;
    }

    public function getHTML()
    {
        return $this->html;
    }
}