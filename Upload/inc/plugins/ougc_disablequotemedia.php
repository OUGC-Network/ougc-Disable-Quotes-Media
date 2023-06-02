<?php

/***************************************************************************
 *
 *    OUGC Disable Quotes Media plugin (/inc/plugins/ougc_disablequotemedia.php)
 *    Author: Omar Gonzalez
 *    Copyright: © 2020 - 2023 Omar Gonzalez
 *
 *    Website: https://ougc.network
 *
 *    Convert image and video embeds inside quotes to links.
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

declare(strict_types=1);

// Die if IN_MYBB is not defined, for security reasons.
if (!defined('IN_MYBB')) {
    die('This file cannot be accessed directly.');
}

// Run our hook.
if (!defined('IN_ADMINCP')) {
    global $plugins, $templatelist;

    $plugins->add_hook('parse_message_me_mycode', 'ougc_disablequotemedia_parser');

    if (isset($templatelist)) {
        $templatelist .= ',';
    } else {
        $templatelist = '';
    }

    $templatelist .= 'ougcdisablequotemedia';
}

const OUGC_DISABLEQUOTEMEDIA_PATTERN = "#\[quote\](.*?)\[\/quote\](\r\n?|\n?)#si";

const OUGC_DISABLEQUOTEMEDIA_PATTERN_COMPLEX = "#\[quote=([\"']|&quot;|)(.*?)(?:\\1)(.*?)(?:[\"']|&quot;)?\](.*?)\[/quote\](\r\n?|\n?)#si";

// PLUGINLIBRARY
if (!defined('PLUGINLIBRARY')) {
    define('PLUGINLIBRARY', MYBB_ROOT . 'inc/plugins/pluginlibrary.php');
}

// Plugin API
function ougc_disablequotemedia_info(): array
{
    return [
        'name' => 'OUGC Disable Quotes Media',
        'description' => 'Convert image and video embeds inside quotes to links.',
        'website' => 'https://community.mybb.com/mods.php?action=view&pid=1397',
        'author' => 'Omar G.',
        'authorsite' => 'https://ougc.network',
        'version' => '1.8.33',
        'versioncode' => 1833,
        'compatibility' => '183*',
        'codename' => 'ougc_ougc_disablequotemedia',
        'pl' => [
            'version' => 13,
            'url' => 'http://community.mybb.com/mods.php?action=view&pid=573'
        ]
    ];
}

// This function runs when the plugin is activated.
function ougc_disablequotemedia_activate(): void
{
    global $PL, $cache, $lang;

    if ($fileExists = file_exists(PLUGINLIBRARY) && !($PL instanceof PluginLibrary)) {
        require_once PLUGINLIBRARY;
    }

    $info = \ougc_disablequotemedia_info();

    if (!$fileExists || $PL->version < $info['pl']['version']) {
        \flash_message(
            $lang->sprintf(
                'This plugin requires <a href="{1}">PluginLibrary</a> version {2} or later to be uploaded to your forum.',
                $info['pl']['url'],
                $info['pl']['version']
            ),
            'error'
        );

        \admin_redirect('index.php?module=config-plugins');
    }

    // Add template group
    $PL->templates('ougcdisablequotemedia', 'OUGC Disable Quotes Media', [
        '' => '<blockquote class="mycode_quote"><cite>{$lang->quote}</cite>$1</blockquote><br />',
    ]);

    // Insert/update version into cache
    $pluginList = (array)$cache->read('ougc_plugins');

    if (!isset($pluginList['disablequotemedia'])) {
        $pluginList['disablequotemedia'] = $info['versioncode'];
    }

    /*~*~* RUN UPDATES START *~*~*/

    /*~*~* RUN UPDATES END *~*~*/

    $pluginList['disablequotemedia'] = $info['versioncode'];

    $cache->update('ougc_plugins', $pluginList);
}

// Checks to make sure plugin is installed
function ougc_disablequotemedia_is_installed(): bool
{
    global $cache;

    $pluginList = (array)$cache->read('ougc_plugins');

    return isset($pluginList['disablequotemedia']);
}

// This function runs when the plugin is uninstalled.
function ougc_disablequotemedia_uninstall(): void
{
    global $PL, $cache;

    if (!($PL instanceof PluginLibrary)) {
        require_once PLUGINLIBRARY;
    }

    $PL->templates_delete('ougcdisablequotemedia');

    // Delete version from cache
    $pluginList = (array)$cache->read('ougc_plugins');

    if (isset($pluginList['disablequotemedia'])) {
        unset($pluginList['disablequotemedia']);
    }

    if (!empty($pluginList)) {
        $cache->update('ougc_plugins', $pluginList);
    } else {
        $cache->delete('ougc_plugins');
    }
}

// Hook: parse_message_me_mycode
function ougc_disablequotemedia_parser(string &$message)
{
    global $parser, $post;

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

        $message = preg_replace_callback(
            OUGC_DISABLEQUOTEMEDIA_PATTERN,
            'ougc_disablequotemedia_simple',
            $message,
            -1,
            $count
        );

        $message = preg_replace_callback(
            OUGC_DISABLEQUOTEMEDIA_PATTERN_COMPLEX,
            'ougc_disablequotemedia_callback',
            $message,
            -1,
            $count_callback
        );

        if (!$message) {
            $message = $previous_message;

            break;
        }
    } while ($count || $count_callback);
}

function ougc_disablequotemedia_simple(array $matches): string
{
    if (empty($matches) || empty($matches[0])) {
        return '';
    }

    global $lang, $templates;

    $replace = eval($templates->render('ougcdisablequotemedia', true, false));

    $message = \preg_replace(OUGC_DISABLEQUOTEMEDIA_PATTERN, $replace, $matches[0]);

    return \ougc_disablequotemedia_helper($message);
}

function ougc_disablequotemedia_callback(array $matches): string
{
    global $parser;

    if (empty($matches) || empty($matches[0])) {
        return '';
    }

    $message = $parser->mycode_parse_post_quotes_callback1($matches);

    return \ougc_disablequotemedia_helper($message);
}

function ougc_disablequotemedia_helper(string &$message): string
{
    global $parser;

    if (!empty($parser->options['allow_imgcode'])) {
        $message = preg_replace_callback(
            "#\[img\](\r\n?|\n?)(https?://([^<>\"']+?))\[/img\]#is",
            [$parser, 'mycode_parse_img_disabled_callback1'],
            $message
        );

        $message = preg_replace_callback(
            "#\[img=([1-9][0-9]*)x([1-9][0-9]*)\](\r\n?|\n?)(https?://([^<>\"']+?))\[/img\]#is",
            [$parser, 'mycode_parse_img_disabled_callback2'],
            $message
        );

        $message = preg_replace_callback(
            "#\[img align=(left|right)\](\r\n?|\n?)(https?://([^<>\"']+?))\[/img\]#is",
            [$parser, 'mycode_parse_img_disabled_callback3'],
            $message
        );

        $message = preg_replace_callback(
            "#\[img=([1-9][0-9]*)x([1-9][0-9]*) align=(left|right)\](\r\n?|\n?)(https?://([^<>\"']+?))\[/img\]#is",
            [$parser, 'mycode_parse_img_disabled_callback4'],
            $message
        );
    }

    if (!empty($parser->options['allow_videocode'])) {
        $message = preg_replace_callback("#\[video=(.*?)\](.*?)\[/video\]#i", array($parser, 'mycode_parse_video_disabled_callback'), $message);
    }

    return $message;
}