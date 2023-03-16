<?php

namespace Weglot\Parser\Check;

use Weglot\Parser\Check\Regex\RegexChecker;
use Weglot\Util\SourceType;
use WGSimpleHtmlDom\simple_html_dom;
use WGSimpleHtmlDom\simple_html_dom_node;
use Weglot\Client\Api\Exception\InvalidWordTypeException;
use Weglot\Client\Api\WordEntry;
use Weglot\Parser\Check\Dom\AbstractDomChecker;
use Weglot\Parser\Parser;
use Weglot\Util\Text;

class RegexCheckerProvider
{

    const DEFAULT_CHECKERS_NAMESPACE = '\\Weglot\\Parser\\Check\\Regex\\';

    /**
     * @var Parser
     */
    protected $parser = null;

    /**
     * @var array
     */
    protected $checkers = [];

    /**
     * @var array
     */
    protected $discoverCaching = [];


    /**
     * DomChecker constructor.
     * @param Parser $parser
     * @param int $translationEngine
     */
    public function __construct(Parser $parser)
    {
        $this->setParser($parser);
        $this->loadDefaultCheckers();
    }

    /**
     * @param Parser $parser
     * @return $this
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @return Parser
     */
    public function getParser()
    {
        return $this->parser;
    }


    /**
     * @param $checker
     * @return $this
     */
    public function addChecker($checker)
    {
        $this->checkers[] = $checker;

        return $this;
    }

    /**
     * @param array $checkers
     * @return $this
     */
    public function addCheckers(array $checkers)
    {
        $this->checkers = array_merge($this->checkers, $checkers);

        return $this;
    }

    /**
     * @return array
     */
    public function getCheckers()
    {
        return $this->checkers;
    }

    /**
     * Load default checkers
     */
    protected function loadDefaultCheckers()
    {
        /* Add JSON LD checker */
        if(strpos(implode("," , $this->parser->getExcludeBlocks()), 'application/ld+json') === false &&
           strpos(implode("," , $this->parser->getExcludeBlocks()), '.wg-ldjson') === false
        ) {
            $this->addChecker(new RegexChecker("#<script type=('|\")application\/ld\+json('|\")([^\>]+?)?>(.*?)<\/script>#s" , SourceType::SOURCE_JSON, 4 , array( "description" ,  "name" , "headline" , "articleSection", "text"  )));
        }

        /* Add HTML template checker */
        if(strpos(implode("," , $this->parser->getExcludeBlocks()), 'text/html') === false  &&
           strpos(implode("," , $this->parser->getExcludeBlocks()), '.wg-texthtml') === false
        ) {
            $this->addChecker(new RegexChecker("#<script type=('|\")text/html('|\")([^\>]+?)?>(.+?)<\/script>#s", SourceType::SOURCE_HTML, 4));
        }
    }

    /**
     * @param string $checker   Class of the Checker to add
     * @return bool
     */
    public function register($checker)
    {
        if ($checker instanceof RegexChecker) {
            $this->addChecker($checker);
            return true;
        }
        return false;
    }


    /**
     * @param string $domString
     * @return array
     * @throws InvalidWordTypeException
     */
    public function handle($domString)
    {
        $checkers = $this->getCheckers();
        $regexes = [];
        foreach ($checkers as $class) {
            list($regex, $type, $varNumber, $extraKeys, $callback, $revert_callback) = $class->toArray();
            preg_match_all($regex, $domString, $matches);
            if(isset($matches[$varNumber])) {
                $matches0 = $matches[0];
                $matches1 = $matches[$varNumber];
                foreach ($matches1 as $k => $match) {
                    $new_match = $match;
                    if($callback) {
                        $new_match = call_user_func($callback, $match);
                    }

                    if($type === SourceType::SOURCE_JSON) {
                        $regex = $this->getParser()->parseJSON($new_match, $extraKeys);
                        $regex['source_before_callback'] = $match;
                    }
                    if($type === SourceType::SOURCE_TEXT) {
                        $regex = $this->getParser()->parseText($new_match, $matches0[$k]);
                        $regex['source_before_callback'] = $matches0[$k];
                    }
                    if($type === SourceType::SOURCE_HTML) {
                        $regex = $this->getParser()->parseHTML($new_match);
                        $regex['source_before_callback'] = $match;
                    }
                    $regex['revert_callback'] = $revert_callback;
                    array_push($regexes, $regex);
                }
            }
        }
        return $regexes;
    }


}
