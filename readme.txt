=== Deals ===
Contributors: KestutisIT, mariusslo
Donate link: https://profiles.wordpress.org/KestutisIT
Website link: https://wordpress.org/plugins/deals/
Tags: slider, offers, deals, deal, offer
Requires at least: 4.6
Tested up to: 5.2
Requires PHP: 5.6
Stable tag: trunk
License: MIT License
License URI: https://opensource.org/licenses/MIT

It’s a MIT-licensed (can be used in premium themes), high quality, native and responsive WordPress plugin to create and view slider-based deals


== Description ==

**First** - differently than any other similar plugin, this plugin is based on MIT license, which is a holly-grail for premium theme authors on i.e. ThemeForest or similar marketplaces.
Differently to standard GPLv2 license you are not required to open-source your theme and you **CAN** include this plugin into your premium websites bundle packs.
I do say here **bundle packs**, because you should never have an deals section to be a part of your theme, because that would be a bad idea - you need to leave your customers a flexibility for the future scale:
What if your customers will decide later go with some kind of fancy **e-commerce marketplace** system like in Amazon.com - if your customer will grow that big, he won't need to have deals plugin anymore on their website, he will want to replace it with that fancy **e-commerce marketplace** system.
So my advise is to include this plugin in your bundle pack's `/Optional Plugins/` folder, so that you can tell about in the installation instructions, but make it fully independent from your theme.

**Second** - this plugin is fully **MVC + Templates** based. This means that it's code is not related at all to it's UI, and that allows you easily to override it's UI templates and Assets (CSS, JS, Images) by your theme very easily (and there is detailed step-by-step instructions given how to do that).
This means that you making a theme to be what the theme has to be - a UI part of your website, nothing more.

**Third** - it is much more secure than any other plugin's on the market. It is based on top-end S.O.L.I.D. coding principle with input data validation with data-patterns, output escaping.

**Fourth** - this plugin is scalable – it’s source code is fully object-oriented, clean & logical, based on MVC architectural pattern with templates engine, compliant with strict PSR-2 coding standard and PSR-4 autoloaders, and easy to understand how to add new features on your own.

**Fifth** - this plugin works well with big databases & high-traffic websites – it is created on optimal BCNF database structure and was tested on live website with 1M customers database and 500,000 active daily views.

**Sixth** - it does support official WordPress multisite as network-enabled plugin, as well as it does have support WPML string translation.
At this point, if you need more than one language, I'd strongly advise to go with official WordPress multisite setup, because it is free, it is official (so you will never need to worry about the future support), and, most important - WordPress multisite is much more suitable for websites that needs to scale. You don't want to have that additional translation bottle-neck code layer to be processed via database.

**Seventh** - it has nice user experience - it's has a default design, it does allow you to have more than 3 deals via different slider's slides, as well as fading in and out description on mouse hover - so it is not static like what you usually get with Gutenberg.

**But the most important** is that this plugin is and always be **ads-free**. I personally really hate these **freemium**, **ads-full** or **tracking** plugins which makes majority of the plugins on w.org plugins directory (and, actually, many of premium marketplaces). So this is the key features we always maintain:
1. Never track your data (nor even by putting some kind of GDPR-compliance agreement checkbox, like `Error Log Monitor` plugin),
2. Never make it pseudo-ads-full (even such a big plugins like `WooCommerce` or `Contact Form 7` has nearly 80% of their home screen or 20% of their main buttons about `how to install \ buy other plugins`
- this is a really ugly behavior of pushing-more and going to Facebook-like business, where you get like drug-addicted to company products).

The goal of this plugin is to full-fill the needs of website-starter, that needs a great tool which can last him for many years until it will grow that big so he would grow-out current plugins and would need some kind of different plugins.

And, I believe, that many other developers had the same issue when tried to create their first premium theme or set-up a website for their client. Starting with the issues with license type to the moment when deals section is `hardcoded` into theme code.

So I wanted to help all these developers to save their time, and I'm releasing this plugin for you to simplify your work. And I'm releasing it under MIT license, which allows you to use this plugin your website bundle without any restrictions for both - free and commercial use.

Plus - I'm giving a promise to you, that this plugin is and will always be 100% free, without any ads, 'Subscribe', 'Follow us', 'Check our page', 'Get Pro Version' or similar links.

Finally - the code is poetry - __the better is the web, the happier is the world__.

- - - -
== Live Demo ==
[Deals (Live Demo)](https://nativerental.com/cars/ "Deals (Live Demo)")

== GitHub Repository (for those, who want to contribute via "Pull Requests") ==
[https://github.com/SolidMVC/Deals](https://github.com/SolidMVC/Deals "Deals @GitHub")

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `Deals` (or `deals`) to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
2.1. If your theme **does not* support FontAwesome icons, please **enable** FontAwesome in Deals -> Settings -> "Global" tab.
3. Go to admin menu item `Deals` -> `Deal Manager` and add deals.
4. Now create a page by clicking the [Add New] button under the page menu.
5. Add `[deals display="deals" layout="slider"]` shortcode to page content and click on `[Publish]` button.
6. In WordPress front-end page, where you added deals slider shortcode, you will see slider-based deals.
7. Congratulations, you're done! We wish you to have a pleasant work with our Deals Plugin for WordPress.


== Frequently Asked Questions ==

= Does it supports multiple slides of deals? =

Yes, this plugin does have support for `Slick Slider` that is seen it you have more than 3 deals.

= Does it allows to assign a deal a custom / external target URL? =

Yes, you can assign any target URL to any deal you want to.

= Does it support URL parameters? =

Yes, if your DEAL ID is i.e. `4` (you can get your DEAL ID from `Deals` -> `Deal Manager`), then you can go
to your website's deal page and show only desired deal by following this URL structure:

`
<YOUR-SITE>.com/<DEAL-PAGE>/?deal=4
`


== Screenshots ==

1. Deals - Front-End Deals Slider
2. Deals - Front-End Deal Hover with Description
3. Deals - Responsive View of Deals Slider
4. Deals - Admin Deal Manager
5. Deals - Admin Global Settings
6. Deals - Admin Import Demo
7. Deals - Admin Plugin Updating
8. Deals - Admin Plugin Status
9. Deals - Admin User Manual


== Changelog ==

= 6.1.10 =
* Semver alignment with other SolidMVC models.

= 6.1.9 =
* Fixed compatibility style routing bug
* Refactored time(UTC)
* HTTP changed to HTTPS
* Refactored CSSFile to CSS_File
* Improved CSS

= 6.1.8 =
* Fixed minor bug with demoDealImageURL.
* Gallery folder routing improved.
* Added CSS improvements.
* Description is now shown when hovering on deal‘s thumbnail.
* Added URL button to description when in tablet/mobile view.
* Improved JavaScript selectors.
* Fixed bug when slide only has one deal and is not pre-selected.
* Added white background when no image is on deal.
* Fixed bug when hover effect only happens on title of deal.
* Fixed positioning when JavaScript is not available.
* Fixed overflowing issues.
* Improved language file.
* Deal title required PHP check added.
* Minor HTML improvements.
* Previous slide arrow is now visible on white background on last slide with 1 deal.
* Added segregated core and plugin language files.
* Global plugin lang path issue fixed.
* Improved language.
* License line text patched.
* Duplicating demo UID check added to demos observer.
* CSS class naming improved.

= 6.1.7 =
* Initial public release! Based on S.O.L.I.D. MVC Engine, Version 6 (without extensions).


== Upgrade Notice ==

= 6.1.7+ =
* Just drag and drop new plugin folder or click 'update' in WordPress plugin manager.

= 6.1.7 =
* Initial public release!
