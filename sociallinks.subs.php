<?php

/**
 * @package "SocialLinks" addon for ElkArte
 * @author Spuds
 * @copyright (c) 2014 Spuds
 * @license Mozilla Public License version 1.1 http://www.mozilla.org/MPL/1.1/.
 *
 * @version 0.2
 *
 */

if (!defined('ELK'))
{
	die('No access...');
}

/**
 * Integration hook, integrate_load_theme
 */
function ilt_sociallinks()
{
	global $modSettings;

	// Make sure we need to do anything
	if (empty($modSettings['sociallinks_onoff']))
	{
		return;
	}

	loadCSSFile('sociallinks.css');
}

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
		array('check', 'sl_whatsapp'),
		array('check', 'sl_telegram'),
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
	global $modSettings, $context, $scripturl, $txt;

	// Make sure we need to do anything
	if (empty($modSettings['sociallinks_onoff']))
	{
		return;
	}

	// If this is this the first message in the thread
	if ($output['id'] == $context['topic_first_message'])
	{
		loadLanguage('sociallinks');

		// Set the div class
		$style = empty($output['attachment']) ? 'class="floatleft slink"' : 'class="slink_attach"';

		$output['body'] .= '
			</div>
			<div ' . $style . '>
				<ul class="sl-buttons">';

		// Show Twitter Tweet button
		if (!empty($modSettings['sl_twitter']))
		{
			$output['body'] .= '
					<li class="sl-t"><a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $scripturl . '?topic=' . $context['current_topic'] . '" data-counturl="' . $scripturl . '?topic=' . $context['current_topic'] . '" data-text="' . $context['page_title_html_safe'] . '"></a></li>';

			loadJavascriptFile('https://platform.twitter.com/widgets.js', array('async' => 'true', 'defer' => 'true'));
		}

		// Show Google +1 button
		if (!empty($modSettings['sl_googleplus']))
		{
			$output['body'] .= '
					<li class="sl-g"><div class="g-plus" data-action="share" data-annotation="none" data-height"28" data-href="' . $scripturl . '?topic=' . $context['current_topic'] . '"></div></li>';

			loadJavascriptFile('https://apis.google.com/js/platform.js', array('async' => 'true', 'defer' => 'true'));
		}

		// Show LinkedIn button
		if (!empty($modSettings['sl_linkedin']))
		{
			$output['body'] .= '
					<li class="sl-l"><script type="IN/Share" data-url="' . $scripturl . '?topic=' . $context['current_topic'] . '"></script></li>';

			loadJavascriptFile("https://platform.linkedin.com/in.js", array('async' => 'true', 'defer' => 'true'));
		}

		// Show whatsapp share button
		if (!empty($modSettings['sl_whatsapp']))
		{
			$output['body'] .= '
					<li class="sl-w"><a href="whatsapp://send" data-text="' . $context['page_title_html_safe'] . '" data-href="' . $scripturl . '?topic=' . $context['current_topic'] . '" class="wa_btn wa_btn_s" style="display:none">' . $txt['sl_share'] . '</a>';

			loadJavascriptFile("https://cdn.jsdelivr.net/whatsapp-sharing/1.3.3/whatsapp-button.js", array('defer' => 'true'));
			addInlineJavascript('$(document).ready(function () {WASHAREBTN.crBtn();})', true);
		}

		// Show telegram share
		if (!empty($modSettings['sl_telegram']))
		{
			$output['body'] .= '
					<li class="sl-tg"><a href="tg://share?url=' . $scripturl . '?topic=' . $context['current_topic'] . '?t=12&text=' . $context['page_title_html_safe'] . '"><i class="sl-icon icon-tg"></i>' . $txt['sl_share'] . '</a></li>';
		}

		// Show Facebook Like button
		if (!empty($modSettings['sl_facebook']))
		{
			$output['body'] .= '
					<li class="sl-f"><iframe src="https://www.facebook.com/plugins/like.php?href=' . $scripturl . '?topic=' . $context['current_topic'] . '&width=50&layout=button&action=like&show_faces=false&share=false&height=20&appId" width="50" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe></li>';
		}

		$output['body'] .= '
			</ul>';
	}
}
