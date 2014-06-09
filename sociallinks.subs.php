<?php

/**
 * @package "SocialLinks" addon for ElkArte
 * @author Spuds
 * @copyright (c) 2014 Spuds
 * @license Mozilla Public License version 1.1 http://www.mozilla.org/MPL/1.1/.
 *
 * @version 0.1
 *
 */

if (!defined('ELK'))
	die('No access...');

/**
 * Integration hook, integrate_general_mod_settings
 *
 * - Not a lot of settings for this addon so we add them under the predefined
 * Miscellaneous area of the forum
 *
 * @param mixed[] $config_vars
 */
function igm_sociallinks(&$config_vars)
{
	global $txt;

	loadLanguage('sociallinks');

	$config_vars = array_merge($config_vars, array(
		'',
		array('check', 'sociallinks_onoff', 'subtext' => $txt['sociallinks_onoff_desc']),
		array('check', 'sl_facebook'),
		array('check', 'sl_twitter'),
		array('check', 'sl_googleplus'),
	));
}

/**
 * ipdc_sociallinks()
 *
 * - Display Hook, integrate_prepare_display_context, called from Display.controller
 * - Used to interact with the message array before its sent to the template
 *
 * @param mixed[] $output
 * @param mixed[] $message
 */
function ipdc_sociallinks(&$output, &$message)
{
	global $modSettings, $context, $scripturl;

	// Make sure we need to do anything
	if (empty($modSettings['sociallinks_onoff']) || (empty($modSettings['sl_facebook']) && empty($modSettings['sl_twitter']) && empty($modSettings['sl_googleplus'])))
		return;

	// If this is this the first message in the thread
	if ($output['id'] == $context['topic_first_message'])
	{
		// Yes ugly inline css
		$output['body'] .= '
			<div class="floatleft" style="margin: 10px 0 0; padding: 8px 0;">';

		// Show Facebook Like button
		if (!empty($modSettings['sl_facebook']))
			$output['body'] .= '
				<iframe src="http://www.facebook.com/plugins/like.php?href=' . $scripturl . '?topic=' . $context['current_topic'] . '&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=20" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:20px;" allowTransparency="true"></iframe>';

		// Show Twitter Tweet button
		if (!empty($modSettings['sl_twitter']))
			$output['body'] .= '
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $scripturl . '?topic=' . $context['current_topic'] . '" data-counturl="' . $scripturl . '?topic=' . $context['current_topic'] . '"></a><script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';

		// Show Google +1 button
		if (!empty($modSettings['sl_googleplus']))
			$output['body'] .= '
				<div class="g-plusone" data-size="medium"></div><script type="text/javascript">(function() {var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;po.src = "https://apis.google.com/js/plusone.js";var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);})();</script>';

		$output['body'] .= '
			</div>';
	}
}