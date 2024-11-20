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
            'onPageInitialized' => ['onPageInitialized', 0],
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
        return;
    }

    /**
     * Expose glossary terms to JavaScript
     */
    public function onPageInitialized()
    {
        // Ensure this method runs only on the front-end
        if ($this->isAdmin()) {
            return;
        }

        // Get config
        $pluginConfig = $this->config->get('plugins.glossary', array());

        // Check if abbreviations are enabled and get terms
        if (!empty($pluginConfig['abbreviations']) && !empty($pluginConfig['definitions'])) {
            $terms = $pluginConfig['definitions'];

            // Add script with glossary terms to the head section
            $this->grav['assets']->addInlineJs(
                'window.glossaryTerms = ' . json_encode($terms) . ';'
            );
        }
    }
}
