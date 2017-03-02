<?php

/**
 * @package "SocialLinks" addon for ElkArte
 * @author Spuds
 * @copyright (c) 2014-2017 Spuds
 * @license Mozilla Public License version 1.1 http://www.mozilla.org/MPL/1.1/.
 *
 * @version 0.2
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
		array('check', 'sl_linkedin'),
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
		$style = empty($output['attachment']) ? 'class="floatleft" style="margin: 15px 0 0;"' : 'style="text-align: left;margin: 15px 0 0;"';

		$output['body'] .= '
			</div><div ' . $style . '>';

		// Show Twitter Tweet button
		if (!empty($modSettings['sl_twitter']))
			$output['body'] .= '<a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $scripturl . '?topic=' . $context['current_topic'] . '" data-counturl="' . $scripturl . '?topic=' . $context['current_topic'] . '" data-text="'. $context['page_title_html_safe'] .'"></a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>';

		// Show Google +1 button
		if (!empty($modSettings['sl_googleplus']))
			$output['body'] .= '
				<div class="g-plus" data-action="share" data-annotation="bubble" data-height"28" data-href="' . $scripturl . '?topic=' . $context['current_topic'] . '"></div><script>(function() {var po=document.createElement("script");po.type="text/javascript";po.async=true;po.src="https://apis.google.com/js/platform.js";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(po, s);})();</script>';

		// Show LinkedIn button
		if (!empty($modSettings['sl_linkedin']))
			$output['body'] .= '
				<span style="display: inline-block;vertical-align: top"><script src="//platform.linkedin.com/in.js"> lang: en_US</script><script type="IN/Share" data-url="' . $scripturl . '?topic=' . $context['current_topic'] . '" data-counter="right"></script></span>';

		// Show Facebook Like button
		if (!empty($modSettings['sl_facebook']))
			$output['body'] .= '
            	<iframe src="https://www.facebook.com/plugins/like.php?href=' . $scripturl . '?topic=' . $context['current_topic'] . '&width=90&layout=button_count&action=like&show_faces=false&share=false&height=20&appId" width="90" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>';
	}
}
