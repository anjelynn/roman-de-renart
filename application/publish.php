#!/usr/bin/php
<?php
/**
 * Roman de Renart.
 *
 * Command line to publish episodes or update the table of contents
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2015 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 *
 * @link      https://roman-de-renart.blogspot.com/
 */
require_once 'Blog.php';
require_once 'common.php';
require_once 'Text.php';

define('OPTION_A', '-c -i -t');

/**
 * The command help.
 */
$help =
'Usage:
-a              Options: %1$s.
-c              Update the copyright widget with the current year.
-i              Update the introduction widget with the number of
                the last translated verse.
-l              Display the list of episodes.
-n number,...   Optional comma separated list of numbers of episodes.
                By default, all episodes are processed.
                Mandatory in logged off mode, only one number allowed.
                999 is the number of the episode being translated.
-p password     Blogger account Password.
-t              Update the table of contents widget.
-u name         Blogger user/email/login name.
-v              Verification mode only: no publishing, no file changes.

Notes:
In logged on mode, the episode HTML is created/updated in the messages directory.
In logged off mode, the episode HTML is stored in messages/temp.html.

You need to be authorized to be able to publish.
Run "authorize -h" for more information.

Examples:
# publish episode(s) in Blogger
publish -u abc -p xyz

# publish episodes 10 and 11 in Blogger
publish -u abc -p xyz -n 10,11

# create/update episode 10 in messages/temp.html
publish -n 10

# list the episodes that have changed and need to be published
publish -v
';

try {
    if (! $options = getopt('haciln:p:tu:v')) {
        throw new Exception('invalid or missing option(s)');
    }

    $options += read_password_file();

    if (isset($options['h'])) {
        // displays the command usage (help)
        exit(sprintf($help, OPTION_A));
    }

    if (isset($options['a'])) {
        // this is the (combined) option A, adds the options
        preg_match_all('~\w~', (string) OPTION_A, $matches);
        $options += array_fill_keys($matches[0], false);
        unset($options['a']);
    }

    $text     = new Text();
    $episodes = $text->parseFile();

    if (isset($options['l'])) {
        // displays the list of episodes
        echo $text->listEpisodes($episodes);
        exit;
    }

    if ($verification_only = isset($options['v'])) {
        // this is the verification mode, there will be no publishing and no file changes
        if (isset($options['n'])) {
            $numbers  = explode(',', $options['n']);
            $episodes = array_intersect_key($episodes, array_flip($numbers));
        }

        $htmls = array_map(array($text, 'makeMessage'), $episodes);
        echo "\n" . $text->saveMessages($htmls, $episodes) . "\n";
    } elseif (isset($options['u']) and isset($options['p'])) {
        // this is the logged on mode, publishes one more episodes in Blogger and saves them in local files
        if (isset($options['n'])) {
            $numbers  = explode(',', $options['n']);
            $episodes = array_intersect_key($episodes, array_flip($numbers));
        }

        $htmls = array_map(array($text, 'makeMessage'), $episodes);
        $blog  = new Blog($options['u'], $options['p']);
        echo "\n" . $text->saveMessages($htmls, $episodes, $blog) . "\n";
    } elseif (isset($options['u']) or isset($options['p'])) {
        throw new Exception('missing user name or password');
    }

    if (isset($options['n'])) {
        // this is the logged off mode, makes an episode HTML and saves the content in messages/temp.html
        $number = $options['n'];

        if (! isset($episodes[$number])) {
            throw new Exception('invalid episode number');
        }

        $html = $text->makeMessage($episodes[$number]);
        echo "\n" . $text->saveTempMessage($html, $number) . "\n";
    }

    if (isset($options['c'])) {
        // updates the copyright
        $html = $text->updateCopyright();
        echo "\n" . $text->saveWidget($html, 'copyright.html', 'copyright', $verification_only) . "\n";
    }

    if (isset($options['i'])) {
        // updates the introduction with the number of the last translated verse
        $html = $text->updateIntroduction($episodes);
        echo "\n" . $text->saveWidget($html, 'introduction.html', 'introduction', $verification_only) . "\n";
    }

    if (isset($options['t'])) {
        // creates the table of contents
        $html = $text->makeTableOfContents($episodes);
        echo "\n" . $text->saveWidget($html, 'table-of-contents.html', 'table of contents', $verification_only) . "\n";
    }
} catch (Throwable $e) {
    echo $e->getMessage();
}
