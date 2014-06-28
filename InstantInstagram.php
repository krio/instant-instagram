<?php

class InstantInstagram
{
    private $html;
    private $client_id;
    private $user_id;
    private $num_images;
    private $cache_minutes;

    public function __construct($client_id, $user_id, $num_images, $cache_minutes)
    {
        $this->client_id = trim($client_id);
        $this->user_id = trim($user_id);
        $this->num_images = intval(trim($num_images));
        $this->cache_minutes = intval(trim($cache_minutes));

        if (!$this->num_images) $this->num_images = 6;
        if (!$this->cache_minutes) $this->cache_minutes = 20;

        $this->html = $this->imagesToHTML($this->fetchRecent());
    }

    private function imagesToHTML($images)
    {
        $html = '<div class="instant-instagram-wrap">';
        foreach ($images as $insta_entry_k => $insta_entry) {
            $img_classes = array();
            if ($insta_entry_k === 0) {
                $url = $insta_entry['images']['standard_resolution']['url'];
            }
            else {
                $img_classes[] = 'instant-instagram-small';
                $url = $insta_entry['images']['thumbnail']['url'];
            }
            $img = '<img class="' . implode(' ', $img_classes) . '" src="' . $url . '"/>';
            $html .= '<a onclick="return instant_instagram_anchor_click(this);"
                target="_blank" title="' . $insta_entry['caption']['text'] . '"
                class="noHover" href="' . $insta_entry['link'] . '">' . $img . '</a>';
        }
        $html .= '</div>';
        return $html;
    }

    private function fetchRecent()
    {
        $transient_name = "i_i_recent_{$this->client_id}_{$this->user_id}_{$this->num_images}_{$this->cache_minutes}";
        $transient = get_transient($transient_name);

        if (false === $transient) {
            $insta_params = ['client_id' => $this->client_id, 'count' => $this->num_images];
            $insta_endpoint = 'https://api.instagram.com/v1/users/' . urlencode($this->user_id) . '/media/recent/?';
            foreach ($insta_params as $ip_k => $ip_v) {
                $insta_endpoint .= '&' . urlencode($ip_k) . '=' . urlencode($ip_v);
            }
            $transient = array(
                'fetched_at' => strtotime('now'),
                'response' => json_decode(file_get_contents($insta_endpoint), true)
            );
            set_transient($transient_name, $transient, 20 * MINUTE_IN_SECONDS);
        }

        $images = array();
        if ($transient && $transient['response']) {
            foreach ($transient['response']['data'] as $insta_entry) {
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
