<?php
class InstantInstagram
{
	private $users;
    private $html;
    private $num_images;
    private $cache_minutes;

	/**
	 * @param InstagramUser[] $users
	 * @param int $num_images
	 * @param int $cache_minutes
	 */
    public function __construct($users, $num_images, $cache_minutes)
    {
	    $this->users = $users;
        $this->num_images = $num_images;
        $this->cache_minutes = $cache_minutes;

        if (!$this->num_images) $this->num_images = 6;
        if (!$this->cache_minutes) $this->cache_minutes = 20;

	    $images = array();
        foreach($this->users as $user) {
	        $images = array_merge($images, $this->fetchRecent($user));
	    }

	    usort($images, function($a, $b) {
		    return $b['created_time'] - $a['created_time'];
	    });

	    $this->html = $this->imagesToHTML(array_slice($images, 0, $num_images));
    }

	public function getHTML()
	{
		return $this->html;
	}

	/**
	 * @param InstagramUser $user
	 *
	 * @return array
	 */
    private function fetchRecent($user)
    {
        $transient_name = "i_i_recent_{$user->getClientId()}_{$user->getUserId()}_{$this->num_images}_{$this->cache_minutes}";
        $transient = get_transient($transient_name);

        if (false === $transient) {
            $insta_params = ['client_id' => $user->getClientId(), 'count' => $this->num_images];
            $insta_endpoint = 'https://api.instagram.com/v1/users/' . urlencode($user->getUserId()) . '/media/recent/?';
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
}
