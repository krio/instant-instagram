<?php
class InstagramUser {
	private $client_id;
	private $user_id;

	public function __construct($client_id, $user_id) {
		$this->client_id = $client_id;
		$this->user_id = $user_id;
	}

	public function getClientId() {
		return $this->client_id;
	}

	public function getUserId() {
		return $this->user_id;
	}
}
