<?php

namespace Weglot\Parser\Check;

use Weglot\Client\Api\Enum\WordType;
use WGSimpleHtmlDom\simple_html_dom;
use WGSimpleHtmlDom\simple_html_dom_node;
use Weglot\Client\Api\Exception\InvalidWordTypeException;
use Weglot\Client\Api\WordEntry;
use Weglot\Parser\Check\Dom\AbstractDomChecker;
use Weglot\Parser\Parser;
use Weglot\Util\Text;

class DomCheckerProvider
{

    /**
     * @var array
     */
    protected $inlineNodes = [
        'a' , 'span',
        'strong', 'b',
        'em', 'i',
        'small', 'big',
        'sub', 'sup',
        'abbr',
        'acronym',
        'bdo',
        'cite',
        'kbd',
        'q', 'u'
    ];

    const DEFAULT_CHECKERS_NAMESPACE = '\\Weglot\\Parser\\Check\\Dom\\';

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
     * @var int
     */
    protected $translationEngine;

    /**
     * DomChecker constructor.
     * @param Parser $parser
     * @param int $translationEngine
     */
    public function __construct(Parser $parser, $translationEngine)
    {
        $this->setParser($parser);
        $this->setTranslationEngine($translationEngine);
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
     * @param array $inlineNodes
     * @return $this
     */
    public function setInlineNodes($inlineNodes)
    {
        $this->inlineNodes = $inlineNodes;

        return $this;
    }

    /**
     * @return array
     */
    public function getInlineNodes()
    {
        return $this->inlineNodes;
    }


    /**
     * @param int $translationEngine
     * @return $this
     */
    public function setTranslationEngine($translationEngine)
    {
        $this->translationEngine = $translationEngine;

        return $this;
    }

    /**
     * @return int
     */
    public function getTranslationEngine()
    {
        return $this->translationEngine;
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
        $this->resetDiscoverCaching();

        return $this->checkers;
    }

    /**
     * @return $this
     */
    public function resetDiscoverCaching()
    {
        $this->discoverCaching = [];

        return $this;
    }

    /**
     * @param $domToSearch
     * @param simple_html_dom $dom
     * @return simple_html_dom_node
     */
    public function discoverCachingGet($domToSearch, simple_html_dom $dom)
    {
        if (!isset($discoverCaching[$domToSearch])) {
            $this->discoverCaching[$domToSearch] = $dom->find($domToSearch);
        }

        return $this->discoverCaching[$domToSearch];
    }

    /**
     * Load default checkers
     */
    protected function loadDefaultCheckers()
    {
        $files = array_diff(scandir(__DIR__ . '/Dom'), ['AbstractDomChecker.php', '..', '.']);
        $checkers = array_map(function ($filename) {
            return self::DEFAULT_CHECKERS_NAMESPACE . Text::removeFileExtension($filename);
        }, $files);

        $this->addCheckers($checkers);
    }

    /**
     * @param string $checker   Class of the Checker to add
     * @return bool
     */
    public function register($checker)
    {
        if ($checker instanceof AbstractDomChecker) {
            $this->addChecker($checker);
            return true;
        }
        return false;
    }

    /**
     * @param string $class
     * @return array
     */
    protected function getClassDetails($class)
    {
        $class = self::CHECKERS_NAMESPACE. $class;
        return [
            $class,
            $class::DOM,
            $class::PROPERTY,
            $class::WORD_TYPE
        ];
    }

    /**
     * @param simple_html_dom $dom
     * @return array
     * @throws InvalidWordTypeException
     */
    public function handle(simple_html_dom $dom)
    {
        $nodes = [];
        $checkers = $this->getCheckers();

        foreach ($checkers as $class) {
            list($selector, $property, $defaultWordType) = $class::toArray();

            $discoveringNodes = $this->discoverCachingGet($selector, $dom);

            if($this->getTranslationEngine() <= 2) { // Old model
               $this->handleOldEngine($discoveringNodes, $nodes , $class, $property, $defaultWordType);
            }
            if($this->getTranslationEngine() == 3)  { //New model

                for ($i = 0; $i < count($discoveringNodes); $i++) {
                    $node = $discoveringNodes[$i];
                    $instance = new $class($node, $property);

                    if ($instance->handle()) {

                        $wordType = $defaultWordType;
                        $attributes = []; // Will contain attributes of merged node so that we can put them back after the API call.

                        if($selector === 'text') {
                            if($node->parent->tag === 'title'){
                                $wordType = WordType::TITLE;
                            }


                            $shift = 0;

                            // If the parent node is eligible, we take it instead and we continue until it's not eligible.
                            while($number = $this->numberOfTextNodeInParentAfterChild($node->parentNode(), $node)) {

                                $node = $node->parentNode();
                                $shift = $number - 1;
                                if($node->tag === 'root')
                                    break;
                            }

                            // We descend the node to see if we can take a child instead, in the case there are wrapping node or empty nodes. For instance, In that case <p><b>Hello</b></p>, it's better to chose node "b" than "p"
                            $node = $this->getMinimalNode($node);

                            //We remove attributes from all child nodes and replace by wg-1, wg-2, etc... Real attributes are saved into $attributes.
                            $node = $this->removeAttributesFromChild($node, $attributes);

                            $i = $i + $shift;
                        }

                        $this->getParser()->getWords()->addOne(new WordEntry($node->$property, $wordType));

                        $nodes[] = [
                            'node' => $node,
                            'class' => $class,
                            'property' => $property,
                            'attributes' => $attributes,
                        ];

                    }
                }
            }

        }
        return $nodes;
    }

    public function handleOldEngine($discoveringNodes, &$nodes, $class, $property, $wordType) {
        foreach ($discoveringNodes as $k => $node) {
            $instance = new $class($node, $property);

            if ($instance->handle()) {
                $this->getParser()->getWords()->addOne(new WordEntry($node->$property, $wordType));

                $nodes[] = [
                    'node' => $node,
                    'class' => $class,
                    'property' => $property,
                ];
            } else {
                if (strpos($node->$property, '&gt;') !== false || strpos($node->$property, '&lt;') !== false) {
                    $node->$property = str_replace(['&lt;', '&gt;'], ['<', '>'], $node->$property);
                }
            }
        }
    }


    // This function is important : It return the number of text node inside a given node, but it count only text node that are inside or after a given child (if no child is given it count everything)
    // If at some point it find a block or a excluded block, it returns false.
    public function numberOfTextNodeInParentAfterChild($node, $child = null, &$countEmptyText = false) {
        $count = 0;
        if($this->isText($node)) {

            if(!$countEmptyText && Text::fullTrim($node->innertext()) != ''
                && !is_numeric(Text::fullTrim($node->innertext()))
                && !preg_match('/^\d+%$/', Text::fullTrim($node->innertext()))
            ) {
                $countEmptyText = true;
            }

            if($countEmptyText) {
                $count++;
            }
        }

        if (is_array($node) || is_object($node)) {
            foreach ($node->nodes as $k => $n) {

                if($n->tag === 'comment') {
                    unset($node->nodes[$k]);
                    continue;
                }


                if ($this->containsBlock($n) || $n->hasAttribute(Parser::ATTRIBUTE_NO_TRANSLATE)) {
                    return false;
                }


                if ($child != null && $n->outertext() == $child->outertext()) {
                    $child = null;
                }

                if ($child == null) {
                    $number = $this->numberOfTextNodeInParentAfterChild($n, null, $countEmptyText);
                    if ($number === false) {
                        return false;
                    } else {
                        $count += $number;
                    }
                }
            }
            $node->nodes = array_values($node->nodes);
        }
        return $count;
    }

    public function getMinimalNode($node) {
        if($this->isText($node)) {
            return $node;
        }

        //We remove unnecessary wrapping nodes
        while(count($node->nodes) == 1)
            $node = $node->nodes[0];

        $notEmptyChild = [];
        foreach ($node->nodes as $n) {
            if(!$this->hasOnlyEmptyChild($n)) {
                $notEmptyChild[] = $n;
            }
        }

        if(count($notEmptyChild) == 1) {
            return $this->getMinimalNode($notEmptyChild[0]);
        }


        return $node;
    }


    public function removeAttributesFromChild($node, &$attributes) {

        foreach ($node->children() as $child) {

            if($child->tag === 'comment') {
                continue;
            }

            $k = count($attributes)+1;
            $attributes['wg-'.$k] = $child->getAllAttributes();
            $child->attr = [];
            $child->setAttribute('wg-'.$k, '');
            $this->removeAttributesFromChild($child, $attributes);
        }

        return $node;
    }

    public function hasOnlyEmptyChild($node) {
        if($this->isText($node)) {
            if(Text::fullTrim($node->innertext()) != '')
               return false;
            else
                return true;
        }

        foreach ($node->nodes as $child) {
            if(!$this->hasOnlyEmptyChild($child))
                return false;
        }
        return true;

    }

    public function isInline($node) {
        return in_array($node->tag, $this->getInlineNodes());
    }

    public function isText($node) {
        return $node->tag === 'text';
    }

    public function isBlock($node) {
        return (!$this->isInline($node) && !$this->isText($node) && !($node->tag === 'br'));
    }

    public function containsBlock($node) {

        if($this->isBlock($node))
            return true;
        else {
            foreach($node->nodes as $n) {
                if($this->containsBlock($n))
                    return true;
            }
            return false;
        }

    }

    public function isInlineOrText($node) {
        return $this->isInline($node) || $this->isText($node);
    }

    public function unsetValue(array $array, $value, $strict = TRUE)
    {
        if(($key = array_search($value, $array, $strict)) !== FALSE) {
            unset($array[$key]);
        }
        return $array;
    }
}
