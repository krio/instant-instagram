Instant Instagram
=========

A simple WordPress plugin for showing Instagram images and video previews.

### Usage

1. Create an Instagram application and get a client id. http://instagram.com/developer/
2. Get your Instagram user id. This is not your username. http://jelled.com/instagram/lookup-user-id
3. Install and activate this plugin like you would any other WordPress plugin. https://github.com/krio/instant-instagram/releases
4. Use the instant-instagram shortcode in your WordPress posts and pages.

### The Shortcode

` [instant-instagram clientid="yourclientidhere" userid="youruseridhere"] `

* clientid is your client id from step 1.
* userid is your user id from step 2.

There are some optional arguments
* number - How many recent images should be fetched and displayed from Instagram. Defaults to 6.
* cache_minutes - How often (in minutes) the plugin should check for Instagram updates. Defaults to 20.

` [instant-instagram clientid="yourclientidhere" userid="youruseridhere" number="6" cache_minutes="20"] `