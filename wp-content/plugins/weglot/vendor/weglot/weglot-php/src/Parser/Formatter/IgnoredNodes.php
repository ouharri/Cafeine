<?php

namespace Weglot\Parser\Formatter;

/**
 * Class IgnoredNodes
 * @package Weglot\Parser\Formatter
 */
class IgnoredNodes
{
    /**
     * @var string
     */
    protected $source;

    /**
     * Nodes to ignore in DOM
     * @var array
     */
    protected $ignoredNodes = [
        'strong', 'b',
        'em', 'i',
        'small', 'big',
        'sub', 'sup',
        'abbr',
        'acronym',
        'bdo',
        'cite',
        'kbd',
        'q',
    ];

    /**
     * IgnoredNodes constructor.
     * @param string $source
     */
    public function __construct($source = null)
    {
        $this->setSource($source);
    }

    /**
     * @param array $ignoredNodes
     * @return $this
     */
    public function setIgnoredNodes($ignoredNodes){
        $this->ignoredNodes = $ignoredNodes;
        return $this;
    }

    /**
     * @return array
     */
    public function getIgnoredNodes(){
        return $this->ignoredNodes;
    }

    /**
     * @param string $source
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }


    /**
     * @param array $matches
     */
    protected function replaceContent($matches)
    {
        $this->setSource(
            str_replace(
                $matches[0],
                '&lt;' .$matches['tag'].str_replace('>', '&gt;', str_replace('<', '&lt;', $matches['more'])). '&gt;' . $matches['content']. '&lt;/' . $matches['tag'] . '&gt;',
                $this->getSource()
            )
        );
    }



    /**
     * Convert < & > for some dom tags to let them able
     * to go through API calls.
     */
    public function handle()
    {
        // time for the BIG regex ...
        $pattern = '#<(?<tag>' .implode('|', $this->ignoredNodes). ')(?<more>\s.*?)?\>(?<content>[^>]*?)\<\/(?<tagclosed>' .implode('|', $this->ignoredNodes). ')>#i';
        $matches = [];

        // Using while instead of preg_match_all is the key to handle nested ignored nodes.
        while (preg_match($pattern, $this->getSource(), $matches)) {
            $this->replaceContent($matches);
        }
    }
}
