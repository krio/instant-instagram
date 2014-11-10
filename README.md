Instant Instagram
=========

A simple WordPress plugin for showing Instagram images and video previews. It has been tested on WordPress 3.9.1.

### Usage

1. Create an Instagram application and get a client id. http://instagram.com/developer/
2. Get your Instagram user id. This is not your username. http://jelled.com/instagram/lookup-user-id
3. Install and activate this plugin like you would any other WordPress plugin. https://github.com/krio/instant-instagram/releases
4. Use the instant-instagram shortcode in your WordPress posts and pages.

### The Shortcode

` [instant-instagram clientid="yourclientidhere" userid="youruseridhere"] `

* clientid1 is your client id from step 1.
* userid1 is your user id from step 2.
* You can have any number of users in one feed. The next user would be clientid2 and userid2 etc... The system will fetch the feeds for each and order them.

There are some optional arguments
* number - How many recent images should be fetched and displayed from Instagram. Defaults to 6.
* cache_minutes - How often (in minutes) the plugin should check for Instagram updates. Defaults to 20.

` [instant-instagram clientid1="yourclientidhere" userid1="youruseridhere" number="6" cache_minutes="20"] `