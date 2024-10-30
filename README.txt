=== JavaScript Miner using CoinHive ===
Contributors: marcobb81
Donate link: https://cnhv.co/14y9 
Tags: mining, coinhive, coin, monero, pow, miner, javascript, coin-hive
Requires PHP: 5.2.4
Requires at least: 3.0.1
Tested up to: 4.8.2
Stable tag: master
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

JSMiner enables Coinhive integration on your site. Coinhive offers a JavaScript miner for the Monero Blockchain that you can embed in your website.

== Description ==

JSMiner enables Coinhive integration on your site.
Coinhive offers a JavaScript miner for the Monero Blockchain that you can embed in your website. Your users run the miner directly in their Browser and mine XMR for you in turn for an ad-free experience, in-game currency or whatever incentives you can come up with.
Vist [CoinHive site](https://coinhive.com/) for any details.

Note: Be respectful to your end users and advise them about this plugin use.

== Features ==
Major features/options include:

* Information popup about use of CoinHive integration.
* Background mining with configurable performance different by mobile or laptop, option to stops after seconds or hashes calculated. 
* Captcha on comments, login and register page.
* "Proof of work" verification for external link or internal download link ( media/document file ).
* Custom download page with "Proof of work" verification.
* Protected Posts: Post content will be visible only after "Proof of work" verification. Add tag to post for hide content.
* Widget: simple widget with start/stop button. on widget running user can change performance on low/medium/high. options will be saved for returned user.

**Shortcodes**

Shortcode [javascriptminer_captcha] in page to show a coinhive captcha. Useful on form or contact page.

**Background Mining**

Check "Enabled" checkbox will start miner when page loaded. Configure performance from 1 to 10 for optimal CPU usage, different performance will be set on mobile device.
Options for stop after total hash calculated or after max seconds running.
Options to show an information popup for explain coinhive use

**Captcha**

Enable Captcha on Comments or in Register/Login page. Configure hash value for captcha passing.
Options for autostart.

**Download and External Link**

Enable "Proof of work" for External Link or Document download. Configure it for limit actions on your site or grant access to a resource: user has to solve hashes before they're redirected to the Target URL ( like coinhive shortlink ).
Also different type of POW for document/media is avaiable:
- Modal window: A modal window will appear on User click. User will be enable to download after POW verification. 
- Download Page: Useful for download link. Every attempt to download document/media will redirect user to a download page with POW verification. This feature rewrite url for document/media download (.zip, .pdf, .txt, .doc, .mp3, .wav, .bin, .rar, .iso )
Do you have hotlink problem? this plugin create a new folder "jsminer-download" on wordpress upload folder: every document present in this folder cant be downloaded without pow verification.

**Protected Post**

Post tagged as "protected" will be visible only after captcha verification.
Post tagged as "only running" will be visibile only if miner is running.
Every tag is configurable by option menu.

== Installation ==

Upload the Javascript Miner plugin to your blog, Activate it, then configure it at "Settings > Javascript Miner"
For configure it you have to [register at CoinHive site](https://coinhive.com/account/signup) and enter the public site key from [CoinHive site list](https://coinhive.com/settings/sites).

== Frequently Asked Questions ==

= What is the public key ? =

Go to [CoinHive signup](https://coinhive.com/account/signup) and follow istruction for get one.

= How configure my download page =

1 - Set option "Enable POW for document/media download" to "Download Page"
2 - After go to Pages -> Add new
3 - Set page title to "Download"
4 - ( optional ) Insert in page content a little description like "Please verify that you are not a robot." 
5 - Insert in page content shortcode "[javascriptminer_download]"
6 - Publish page
7 - Enjoy 

Pay attention page will be named "Download" otherwise plugin will not work.

== Screenshots ==

1. Information Popup.
2. Captcha.
3. POW for External Link or Document download.
4. Debug actived.
5. Create Download page.
6. Download page.
7. Basic configuration.
8. Background configuration.
9. Captcha configuration.
10. Download and Link configuration.

== Changelog ==

= 1.6 =
* add plugin update from bitbucket

= 1.5 =
* removed packed script from external domain. add coinhive packed js 

= 1.4.8 =
* improved js script ( thread number not always recognized )

= 1.4.7 =
* added "only running" protected post

= 1.4.6 =
* added information box on pack script use

= 1.4.5 =
* add mode ( exclusive, multitab )  on miner start ( @gregtsourou )

= 1.4.4 =
* fixed css on widget ( @gatewksbury )

= 1.4.3 =
* improved js script

= 1.4.2 =
* fixed bug for cache plugin ( tested on w3 total cache, wp super cache)
* fixed minor bug

= 1.4.1 =
* changed widget design in minimal style ( @drreen )
* updated script resolve adblock/av problems

= 1.4 =
* custom captcha code added
* add different coinhive script host 

= 1.3.1 =
* fixed bug protected post

= 1.3 =
* fixed bug on information popup
* create protected post with tag method
* different version feature

= 1.2.2 =
* fixed bug check token download file

= 1.2.1 =
* rewrite download function

= 1.2.0 =
* add widget simple miner ui from coinhive
* add option for mobile performance

= 1.1.4 =
* Add option to show information popup about CoinHive integration
* Add option to stop miner after tot seconds

= 1.1.3 =
* Javascript remodule
* Add custom "powerd by" on pow window
* Plugin admin page tabbed

= 1.1.2 =
* Limit custom download page for file present in folder '/upload/jsminer-download'. Other download POW type not be affected
* Update link to new coinhive domain

= 1.1.1 =
* Added custom download page.

= 1.1.0 =
* Added Shortcodes.
* Download and External Link POW.
* Minor Bug fix

= 1.0.0 =
* Initial version.

== Upgrade Notice == 
= 1.6 =
* changed plugin hosting from wordpress to bitbucket