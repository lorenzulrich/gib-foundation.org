=== SEO Score Dashboard by SEO-Visuals ===
Contributors: ericververs
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: seo, SEO, google, search engine optimization, yahoo, bing, SEO-Visuals, seo score, seo score dashboard
Requires at least: 3.0.1
Tested up to: 3.6
Stable tag: 1.3.2

Wondering how your website is doing in terms of search engine optimization? Find out in which areas you excel and which areas require your attention.

== Description ==

WordPress automatically solves many SEO issues. However, WordPress can't take care of all SEO factors. [SEO-Visuals](http://www.seo-visuals.com/us) has constructed a website scan that scans a website on various SEO factors (both on-site and off-site). This plugin presents an aggregated result of the SEO website score for your website in the admin panel. The SEO website score is divided in 5 areas, i.e.:

- Onpage score: All SEO factors that have to do with the code for specific pages of your website. For example, the presence of meta tags or the right use of heading tags.
- Website structure score: All SEO factors that have anything to do with internal linking structure and URL structure.
- Technical score: All SEO factors regarding website speed, source code correctness, and search engine crawlability.
- Link building score: How good is your backlink profile? Having highly relevant website link to you is more important than having random backlinks.
- Social Media score: How active are you on social media, and how many people mention or share your website on social media platforms.

This plugin can be used inside your backend administration, such that your visitors will not see your SEO scores.

This plugin uses the external SEO-Visuals API server to calculate the SEO scores. The API server scans the default WordPress domain (home_url) to calculate and return the SEO scores. The SEO scores are returned in JSON format and displayed in the widget after a few minutes of API processing time.
If the last score calculation was performed over 14 days ago a new calculation will be initiated by the external API server.

The used API connection was specially created for private WordPress use and is not intended for other types of usage outside of the WordPress administration, whether commercial or private use.

== Installation ==

1.	Upload the seo-score-dashboard folder to the /wp-content/plugins/ directory.
2.	Activate the SEO Score Dashboard by SEO-Visuals.com plugin through the 'Plugins' menu in WordPress.
3.	Check out your SEO score on the dashboard in the admin panel.

== Screenshots ==

1. The SEO score dashboard as seen in the dashboard menu on in the admin panel.
2. Example page of the downloadable PDF report

== Changelog ==

= 1.3.2 =
* Allow_open_url option bug fixed

= 1.3.1 =
* Speed optimization enhancements

= 1.3 =
* Added graphs for easier interpretation of the scan results.

= 1.2 =
* Added SEO report feature. Users can download a PDF report with a SEO quickscan of their WordPress website.

= 1.1.1 =
* Fixd icon loading bug

= 1.1 =
* Added an explaination to each score. The explaination becomes visible when the user mouse-over the scores.

= 1.0.1 =
* Fixed icon loading bug

= 1.0 =
* First release

== Upgrade Notice ==

= 1.3.2 =
* Allow_open_url option bug fixed

= 1.3.1 =
* Speed optimization enhancements

= 1.3 =
* Added graphs for easier interpretation of the scan results.

= 1.2 =
* Added SEO report feature. Users can download a PDF report with a SEO quickscan of their WordPress website.

= 1.1.1 =
* Fixd icon loading bug

= 1.1 =
* Usability improved, because SEO scores are now explained

= 1.0.1 =
* Fixed icon loading bug

= 1.0 =

-