=== RegLogin ===
Contributors: Francis Mukobi
Donate link: http://frankhost.net
Tags: form, login, registration, editor, lost password, responsive, wpml, internationalization, languages, role, CAPTCHA, honeypot, shortcode, wordpress, frontend
Requires at least: 3.4
Tested up to: 4.2.2
Stable tag: 1.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin for displaying beautiful forms in front-end only using shortcodes. Login and Registration forms

== Description ==

Beautiful responsive Frontend Login and Registration plugin. A plugin for displaying login and register forms through shortcodes.

*   [login_form]
*   [register_form]


### Basics

*   Add your login form in the frontend easily (page or post)
*   And also the registration form
*   If user is logged in, the user will see a custom profile and will be able to edit his/her data in another front-end form
*   One shortcode per form, you only need to create a page or post and apply this shortcode to create each form you want

### Style

*   Every form created is responsive
*   CSS adapted to each theme


== Usage and Settings ==

Please, refer to [Installation section](https://wordpress.org/plugins/reglogin/installation/)

== Screenshots ==

== Upgrade Notice ==

= 1.0 =
*   First installation


== Frequently Asked Questions ==

* No questions asked yet

== Installation ==

### Installation

*   Install **Reglogin** automatically through the WordPress Dashboard or by uploading the ZIP file in the _plugins_ directory.
*   Then, after the package is uploaded and extracted, click on _Activate Plugin_.

Now going through the points above, you should now see a new&nbsp; regLogin_&nbsp;menu item under Settings menu in the sidebar of the admin panel, see figure below of how it looks like.


If you get any error after following through the steps above please contact us through item support comments so we can get back to you with possible helps in installing the plugin and more. On successful activation of this plugin, you should be able to see the login form when you place this shortcode&nbsp;_[clean-login]_&nbsp;in any page or post

* * *

### Settings

Below, the description of each shortcode for use as registration, login, lost password and profile editor forms

*   _[login_form]_ This shortcode contains login form and login information.
*   _[register_form]_ This shortcode contains the register form. If you include in a page/post a link will appear on your login form.


Regarding the widget usage, just place the&nbsp;_RegLogin status and links_&nbsp;widget in the widget area you prefer. It will show the user status and the links to the pages/posts which contains the plugin shortcodes.

Please feel free to contact us if you have any questions.

* * *

### Example

A post/page need to be created by typing the main shortcode&nbsp;_[login_form]_&nbsp;in the content.

When you save or update this post/page you will see the login form.

And also in the setting page&nbsp;_[login_form]_&nbsp;entry will be updated pointing to the current post/page which contains the shortcode (and generates the login form):


We would repeat the same process with the rest of shortcodes if we need it:

*   _[login-register]_&nbsp;to create a registration form

