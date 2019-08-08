<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

class GlossaryPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPageContentRaw' => ['insertAbbreviations', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onGetPageTemplates' => ['onGetPageTemplates', 0],
        ];
    }

    /**
     * Add templates to Twig lookup paths
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__.'/templates';
    }

    /**
     * Add templates to Grav Admin
     */
    public function onGetPageTemplates(Event $event)
    {
        $event->types->scanTemplates(__DIR__."/templates");
    }

    /**
     * Insert abbreviations into the raw content
     *
     * Taken from https://github.com/asmeikal/grav-plugin-acronyms (MIT License).
     * Minor modifications have been made to adapt it for the current use case.
     */
    public function insertAbbreviations(Event $event)
    {
        // Get config
        $pluginConfig = $this->config->get('plugins.glossary', array());

        // Check if abbreviations are enabled
        if (! array_key_exists("abbreviations", $pluginConfig) ||
            ! $pluginConfig["abbreviations"]) {
            return;
        }

        // Get the terms if they exist, exit otherwise
        if (array_key_exists("definitions", $pluginConfig)) {
            $terms = $pluginConfig["definitions"];
        } else {
            return;
        }

        $page = $event['page'];
        $config = $this->mergeConfig($page);

        $enabled = $config->get('enabled');

        // Check that page processes markdown
        $enabled = $enabled && $page->process()['markdown'];

        // Check that markdown extra is enabled
        if (isset($page->header()->markdown) &&
            array_key_exists('extra', $page->header()->markdown)) {
            $enabled = $enabled && $page->header()->markdown['extra'];
        } else {
            $enabled = $enabled && $this->config->get('system.pages.markdown.extra');
        }

        // Inject the abbreviations into the page
        if ($enabled && (count($terms) > 0)) {
            // Get initial content
            $raw = $page->getRawContent();
            $raw .= "\n\n";

            // Append acronyms to page (if abbrev is defined)
            foreach ($terms as $term) {
                if (array_key_exists("abbrev", $term)) {
                    $raw .= "*[${term['abbrev']}]: ${term['term']}\n";
                }
            }

            // Put content back in
            $page->setRawContent($raw);
        }
    }
}
