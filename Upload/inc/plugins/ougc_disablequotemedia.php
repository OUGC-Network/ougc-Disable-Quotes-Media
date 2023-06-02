<?php

/***************************************************************************
 *
 *    OUGC Disable Quotes Media plugin (/inc/plugins/ougc_disablequotemedia.php)
 *    Author: Omar Gonzalez
 *    Copyright: © 2020 Omar Gonzalez
 *
 *    Website: https://ougc.network
 *
 *    Convert images and videos in quotes to links.
 *
 ***************************************************************************
 ****************************************************************************
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ****************************************************************************/

// Die if IN_MYBB is not defined, for security reasons.
defined('IN_MYBB') or die('This file cannot be accessed directly.');

// Run our hook.
if (!defined('IN_ADMINCP')) {
    $plugins->add_hook('parse_message_me_mycode', 'ougc_disablequotemedia_parser');

    global $templatelist;

    if (isset($templatelist)) {
        $templatelist .= ',';
    } else {
        $templatelist = '';
    }

    $templatelist .= 'ougcdisablequotemedia';
}

define('OUGC_DISABLEQUOTEMEDIA_PATTERN', "#\[quote\](.*?)\[\/quote\](\r\n?|\n?)#si");

define('OUGC_DISABLEQUOTEMEDIA_PATTERN_COMPLEX', "#\[quote=([\"']|&quot;|)(.*?)(?:\\1)(.*?)(?:[\"']|&quot;)?\](.*?)\[/quote\](\r\n?|\n?)#si");

// PLUGINLIBRARY
defined('PLUGINLIBRARY') or define('PLUGINLIBRARY', MYBB_ROOT . 'inc/plugins/pluginlibrary.php');

// Plugin API
function ougc_disablequotemedia_info()
{
    return array(
        'name' => 'OUGC Disable Quotes Media',
        'description' => 'Convert images and videos in quotes to links.',
        'website' => 'https://ougc.network',
        'author' => 'Omar G.',
        'authorsite' => 'https://ougc.network',
        'version' => '1.8.1',
        'versioncode' => 1801,
        'compatibility' => '18*',
        'codename' => 'ougc_ougc_disablequotemedia'
    );
}

// This function runs when the plugin is activated.
function ougc_disablequotemedia_activate()
{
    global $PL, $cache;

    $PL || require_once PLUGINLIBRARY;

    // Add template group
    $PL->templates('ougcdisablequotemedia', 'OUGC Disable Quotes Media', array(
        '' => '<blockquote class="mycode_quote"><cite>{$lang->quote}</cite>$1</blockquote><br />',
    ));

    // Insert/update version into cache
    $plugins = $cache->read('ougc_plugins');

    if (!$plugins) {
        $plugins = array();
    }

    $info = ougc_disablequotemedia_info();

    if (!isset($plugins['disablequotemedia'])) {
        $plugins['disablequotemedia'] = $info['versioncode'];
    }

    /*~*~* RUN UPDATES START *~*~*/

    /*~*~* RUN UPDATES END *~*~*/

    $plugins['disablequotemedia'] = $info['versioncode'];

    $cache->update('ougc_plugins', $plugins);
}

// Checks to make sure plugin is installed
function ougc_disablequotemedia_is_installed()
{
    global $cache;

    $plugins = (array)$cache->read('ougc_plugins');

    return isset($plugins['disablequotemedia']);
}

// This function runs when the plugin is uninstalled.
function ougc_disablequotemedia_uninstall()
{
    global $cache;

    $PL || require_once PLUGINLIBRARY;

    $PL->templates_delete('ougcdisablequotemedia');

    // Delete version from cache
    $plugins = (array)$cache->read('ougc_plugins');

    if (isset($plugins['disablequotemedia'])) {
        unset($plugins['disablequotemedia']);
    }

    if (!empty($plugins)) {
        $cache->update('ougc_plugins', $plugins);
    } else {
        $cache->delete('ougc_plugins');
    }
}

// Hook: parse_message_me_mycode
function ougc_disablequotemedia_parser(&$message)
{
    global $parser, $post, $lang;

    if (!(
            $parser instanceof postParser ||
            empty($parser->options['allow_mycode'] ||
                empty($post['pid']))) ||
        (
            empty($parser->options['allow_imgcode']) &&
            empty($parser->options['allow_videocode']
            )
        )) {
        return;
    }

    do {
        $previous_message = $message;

        $message = preg_replace_callback(OUGC_DISABLEQUOTEMEDIA_PATTERN, 'ougc_disablequotemedia_simple', $message, -1, $count);

        $message = preg_replace_callback(OUGC_DISABLEQUOTEMEDIA_PATTERN_COMPLEX, 'ougc_disablequotemedia_callback', $message, -1, $count_callback);

        if (!$message) {
            $message = $previous_message;

            break;
        }
    } while ($count || $count_callback);
}

function ougc_disablequotemedia_simple($matches)
{
    if (empty($matches) || empty($matches[0])) {
        return $matches[0];
    }

    global $lang, $mybb, $templates;

    $replace = eval($templates->render('ougcdisablequotemedia', 1, 0));

    //$replace = "<blockquote class=\"mycode_quote\"><cite>$lang->quote</cite>$1</blockquote>\n";

    $message = preg_replace(OUGC_DISABLEQUOTEMEDIA_PATTERN, $replace, $matches[0]);

    ougc_disablequotemedia_helper($message);

    return $message;
}

function ougc_disablequotemedia_callback($matches)
{
    global $parser;

    if (empty($matches) || empty($matches[0])) {
        return $matches[0];
    }

    $message = $parser->mycode_parse_post_quotes_callback1($matches);

    ougc_disablequotemedia_helper($message);

    return $message;
}

function ougc_disablequotemedia_helper(&$message)
{
    global $parser;

    if (!empty($parser->options['allow_imgcode'])) {
        $message = preg_replace_callback("#\[img\](\r\n?|\n?)(https?://([^<>\"']+?))\[/img\]#is", array($parser, 'mycode_parse_img_disabled_callback1'), $message);
        $message = preg_replace_callback("#\[img=([1-9][0-9]*)x([1-9][0-9]*)\](\r\n?|\n?)(https?://([^<>\"']+?))\[/img\]#is", array($parser, 'mycode_parse_img_disabled_callback2'), $message);
        $message = preg_replace_callback("#\[img align=(left|right)\](\r\n?|\n?)(https?://([^<>\"']+?))\[/img\]#is", array($parser, 'mycode_parse_img_disabled_callback3'), $message);
        $message = preg_replace_callback("#\[img=([1-9][0-9]*)x([1-9][0-9]*) align=(left|right)\](\r\n?|\n?)(https?://([^<>\"']+?))\[/img\]#is", array($parser, 'mycode_parse_img_disabled_callback4'), $message);
    }

    if (!empty($parser->options['allow_videocode'])) {
        $message = preg_replace_callback("#\[video=(.*?)\](.*?)\[/video\]#i", array($parser, 'mycode_parse_video_disabled_callback'), $message);
    }
}