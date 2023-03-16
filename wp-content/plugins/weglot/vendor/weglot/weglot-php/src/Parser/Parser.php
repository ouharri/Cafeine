<?php

namespace Weglot\Parser;

use phpDocumentor\Reflection\DocBlock\Tags\Source;
use Weglot\Client\Api\Enum\WordType;
use Weglot\Client\Api\WordEntry;
use Weglot\Client\Endpoint\CdnTranslate;
use Weglot\Parser\Check\Regex\JsonChecker;
use Weglot\Parser\Check\RegexCheckerProvider;
use Weglot\Parser\Formatter\CustomSwitchersFormatter;
use Weglot\Parser\Formatter\JsonFormatter;
use Weglot\Util\SourceType;
use Weglot\Util\Text;
use WGSimpleHtmlDom\simple_html_dom;
use Weglot\Client\Api\Exception\ApiError;
use Weglot\Client\Api\Exception\InputAndOutputCountMatchException;
use Weglot\Client\Api\Exception\InvalidWordTypeException;
use Weglot\Client\Api\Exception\MissingRequiredParamException;
use Weglot\Client\Api\Exception\MissingWordsOutputException;
use Weglot\Client\Api\TranslateEntry;
use Weglot\Client\Api\WordCollection;
use Weglot\Client\Client;
use Weglot\Client\Endpoint\Translate;
use Weglot\Parser\Check\DomCheckerProvider;

use Weglot\Parser\ConfigProvider\ConfigProviderInterface;
use Weglot\Parser\ConfigProvider\ServerConfigProvider;
use Weglot\Parser\Formatter\DomFormatter;
use Weglot\Parser\Formatter\ExcludeBlocksFormatter;
use Weglot\Parser\Formatter\IgnoredNodes;
use Weglot\Parser\Formatter\JsonLdFormatter;

/**
 * Class Parser
 * @package Weglot\Parser
 */
class Parser {
    /**
     * Attribute to match in DOM when we don't want to translate innertext & childs.
     */
    const ATTRIBUTE_NO_TRANSLATE = 'data-wg-notranslate';
    const ATTRIBUTE_TRANSLATE = 'data-wg-translate';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ConfigProviderInterface
     */
    protected $configProvider;

    /**
     * @var array
     */
    protected $excludeBlocks;

    /**
     * @var array
     */
    protected $whiteList;

    /**
     * @var array
     */
    protected $customSwitchers;

    /**
     * @var string
     */
    protected $languageFrom;

    /**
     * @var string
     */
    protected $languageTo;

    /**
     * @var WordCollection
     */
    protected $words;

    /**
     * @var DomCheckerProvider
     */
    protected $domCheckerProvider;

    /**
     * @var RegexCheckerProvider
     */
    protected $regexCheckerProvider;

    /**
     * @var IgnoredNodes
     */
    protected $ignoredNodesFormatter;

    /**
     * Parser constructor.
     *
     * @param Client $client
     * @param ConfigProviderInterface $config
     * @param array $excludeBlocks
     * @param array $whiteList
     * @param array $customSwitchers
     */
    public function __construct( Client $client, ConfigProviderInterface $config, array $excludeBlocks = [], array $customSwitchers = [], array $whiteList = [] ) {
        $this
            ->setClient( $client )
            ->setConfigProvider( $config )
            ->setExcludeBlocks( $excludeBlocks )
            ->setWhiteList( $whiteList )
            ->setCustomSwitchers( $customSwitchers )
            ->setWords( new WordCollection() )
            ->setDomCheckerProvider( new DomCheckerProvider( $this, $client->getProfile()->getTranslationEngine() ) )
            ->setRegexCheckerProvider( new RegexCheckerProvider( $this ) )
            ->setIgnoredNodesFormatter( new IgnoredNodes() );
    }

    /**
     * @param Client $client
     *
     * @return $this
     */
    public function setClient( Client $client ) {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Client
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * @param array $excludeBlocks
     *
     * @return $this
     */
    public function setExcludeBlocks( array $excludeBlocks ) {
        $this->excludeBlocks = $excludeBlocks;

        return $this;
    }

    /**
     * @return array
     */
    public function getExcludeBlocks() {
        return $this->excludeBlocks;
    }

    /**
     * @param array $whiteList
     *
     * @return $this
     */
    public function setWhiteList( array $whiteList ) {
        $this->whiteList = $whiteList;

        return $this;
    }

    /**
     * @return array
     */
    public function getWhiteList() {
        return $this->whiteList;
    }

    /**
     * @param array $customSwitchers
     *
     * @return $this
     */
    public function setCustomSwitchers( array $customSwitchers ) {
        $this->customSwitchers = $customSwitchers;

        return $this;
    }

    /**
     * @return array
     */
    public function getCustomSwitchers() {
        return $this->customSwitchers;
    }

    /**
     * @param ConfigProviderInterface $config
     *
     * @return $this
     */
    public function setConfigProvider( ConfigProviderInterface $config ) {
        $this->configProvider = $config;

        return $this;
    }

    /**
     * @return ConfigProviderInterface
     */
    public function getConfigProvider() {
        return $this->configProvider;
    }

    /**
     * @param string $languageFrom
     *
     * @return $this
     */
    public function setLanguageFrom( $languageFrom ) {
        $this->languageFrom = $languageFrom;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguageFrom() {
        return $this->languageFrom;
    }

    /**
     * @param string $languageTo
     *
     * @return $this
     */
    public function setLanguageTo( $languageTo ) {
        $this->languageTo = $languageTo;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguageTo() {
        return $this->languageTo;
    }

    /**
     * @param WordCollection $wordCollection
     *
     * @return $this
     */
    public function setWords( WordCollection $wordCollection ) {
        $this->words = $wordCollection;

        return $this;
    }

    /**
     * @return WordCollection
     */
    public function getWords() {
        return $this->words;
    }

    /**
     * @param RegexCheckerProvider $regexCheckerProvider
     *
     * @return $this
     */
    public function setRegexCheckerProvider( RegexCheckerProvider $regexCheckerProvider ) {
        $this->regexCheckerProvider = $regexCheckerProvider;

        return $this;
    }

    /**
     * @return RegexCheckerProvider
     */
    public function getRegexCheckerProvider() {
        return $this->regexCheckerProvider;
    }

    /**
     * @param DomCheckerProvider $domCheckerProvider
     *
     * @return $this
     */
    public function setDomCheckerProvider( DomCheckerProvider $domCheckerProvider ) {
        $this->domCheckerProvider = $domCheckerProvider;

        return $this;
    }

    /**
     * @return DomCheckerProvider
     */
    public function getDomCheckerProvider() {
        return $this->domCheckerProvider;
    }

    /**
     * @param IgnoredNodes $ignoredNodesFormatter
     *
     * @return $this
     */
    public function setIgnoredNodesFormatter( IgnoredNodes $ignoredNodesFormatter ) {
        $this->ignoredNodesFormatter = $ignoredNodesFormatter;

        return $this;
    }

    /**
     * @return IgnoredNodes
     */
    public function getIgnoredNodesFormatter() {
        return $this->ignoredNodesFormatter;
    }

    /**
     * @param string $source
     * @param string $languageFrom
     * @param string $languageTo
     * @param array $extraKeys
     *
     * @return string
     * @throws ApiError
     * @throws InputAndOutputCountMatchException
     * @throws InvalidWordTypeException
     * @throws MissingRequiredParamException
     * @throws MissingWordsOutputException
     */
    public function translate( $source, $languageFrom, $languageTo, $extraKeys = [], $canonical = '' ) {
        // setters
        $this
            ->setLanguageFrom( $languageFrom )
            ->setLanguageTo( $languageTo );

        $results = $this->parse( $source, $extraKeys );

        $tree = $results['tree'];

        if ( $tree['type'] === SourceType::SOURCE_HTML ) {
            $title = $this->getTitle( $tree['dom'] );
        } else {
            $title = "";
        }

        // api communication
        if ( count( $this->getWords() ) === 0 ) {
            return $source;
        }

        $translated = $this->apiTranslate( $title, $canonical );
        $source     = $this->formatters( $source, $translated, $tree );

        return $source;
    }

    /**
     * @param $source
     * @param $extraKeys
     *
     * @return array
     * @throws InvalidWordTypeException
     */
    public function parse( $source, $extraKeys = [] ) {
        $type = self::getSourceType( $source );

        if ( $type === SourceType::SOURCE_HTML ) {
            $tree = $this->parseHTML( $source );
        } elseif ( $type === SourceType::SOURCE_JSON ) {
            $tree = $this->parseJSON( $source, $extraKeys );
        } else {
            $tree = $this->parseText( $source );
        }

        return array( 'tree' => $tree, 'words' => $this->getWords() );
    }

    public function parseHTML( $source ) {
        if ( $this->client->getProfile()->getTranslationEngine() == 2 ) {
            $ignoredNodesFormatter = $this->getIgnoredNodesFormatter();

            $ignoredNodesFormatter->setSource( $source )
                                  ->handle();

            $source = $ignoredNodesFormatter->getSource();
        }

        // simple_html_dom
        $dom = \WGSimpleHtmlDom\str_get_html(
            $source,
            true,
            true,
            WG_DEFAULT_TARGET_CHARSET,
            false
        );

        // if simple_html_dom can't parse the $source, it returns false
        // so we just return raw $source
        if ( $dom === false ) {
            return $source;
        }

        //if whiteList list is not empty we add attr wg-mode-whitelist to the body
        if( !empty( $this->whiteList)){

            foreach ($dom->find('body') as $item)
            {
                $item->setAttribute('wg-mode-whitelist', '');
            }

            if ( ! empty( $this->excludeBlocks ) ) {
                $excludeBlocks = new ExcludeBlocksFormatter( $dom, $this->excludeBlocks, $this->whiteList );
                $dom           = $excludeBlocks->getDom();
            }
        }else{
            // exclude blocks
            if ( ! empty( $this->excludeBlocks ) ) {
                $excludeBlocks = new ExcludeBlocksFormatter( $dom, $this->excludeBlocks );
                $dom           = $excludeBlocks->getDom();
            }
        }

        // checkers
        if(!empty( $this->whiteList)){
            list( $nodes, $regexes ) = $this->checkers( $dom, $source, true );
        }else{
            list( $nodes, $regexes ) = $this->checkers( $dom, $source );
        }

        return [ 'type'    => SourceType::SOURCE_HTML,
                 'source'  => $source,
                 'dom'     => $dom,
                 'nodes'   => $nodes,
                 'regexes' => $regexes
        ];
    }

    public function parseJSON( $jsonString, $extraKeys = [] ) {
        $checker = new  JsonChecker( $this, $jsonString, $extraKeys );

        return $checker->handle();
    }

    public function parseText( $text, $regex = null ) {

        $this->getWords()->addOne( new WordEntry( $text, WordType::TEXT ) );

        return array( "type" => SourceType::SOURCE_TEXT, "source" => $regex, "text" => $text );
    }

    /**
     * @param string $title
     *
     * @return TranslateEntry
     * @throws ApiError
     * @throws InputAndOutputCountMatchException
     * @throws InvalidWordTypeException
     * @throws MissingRequiredParamException
     * @throws MissingWordsOutputException
     */
    protected function apiTranslate( $title = null, $canonical = '' ) {
        // Translate endpoint parameters
        $params = [
            'language_from' => $this->getLanguageFrom(),
            'language_to'   => $this->getLanguageTo()
        ];

        // if data is coming from $_SERVER, load it ...
        if ( $this->getConfigProvider() instanceof ServerConfigProvider ) {
            $this->getConfigProvider()->loadFromServer( $canonical );
        }

        if ( $this->getConfigProvider()->getAutoDiscoverTitle() ) {
            $params['title'] = $title;
        }
        $params = array_merge( $params, $this->getConfigProvider()->asArray() );

        try {
            $translate = new TranslateEntry( $params );
            $translate->setInputWords( $this->getWords() );
        } catch ( \Exception $e ) {
            die( $e->getMessage() );
        }

        $translate = new CdnTranslate($translate, $this->client);
        return $translate->handle();
    }

    /**
     * @param simple_html_dom $dom
     *
     * @return string
     */
    protected function getTitle( simple_html_dom $dom ) {
        $title = 'Empty title';
        foreach ( $dom->find( 'title' ) as $k => $node ) {
            if ( $node->innertext != '' ) {
                $title = $node->innertext;
            }
        }

        return $title;
    }

    /**
     * @param $dom
     * @param $source
     *
     * @return array
     * @throws InvalidWordTypeException
     */
    protected function checkers( $dom, $source ) {
        $nodes   = $this->getDomCheckerProvider()->handle( $dom );
        $regexes = $this->getRegexCheckerProvider()->handle( $source );

        return [
            $nodes,
            $regexes
        ];
    }

    /**
     * @param string $source
     * @param TranslateEntry $translateEntry
     * @param mixed $tree
     * @param int $index
     *
     * @return string $source
     */
    public function formatters( $source, TranslateEntry $translateEntry, $tree, &$index = 0 ) {
        if ( empty( $tree['type'] ) ) {
            return $source;
        }
        if ( $tree['type'] === SourceType::SOURCE_TEXT ) {
            $source = str_replace( $tree['text'], $translateEntry->getOutputWords()[ $index ]->getWord(), $source );
            $index ++;
        }
        if ( $tree['type'] === SourceType::SOURCE_JSON ) {
            $formatter = new JsonFormatter( $this, $source, $translateEntry );
            $source    = $formatter->handle( $tree, $index );
        }
        if ( $tree['type'] === SourceType::SOURCE_HTML ) {
            $formatter = new DomFormatter( $this, $translateEntry );
            $formatter->handle( $tree['nodes'], $index );
            $source = $tree['dom']->save();
            foreach ( $tree['regexes'] as $regex ) {
                if ( empty( $regex['source'] ) ) {
                    continue;
                }
                $translatedRegex = $this->formatters( $regex['source'], $translateEntry, $regex, $index );
                if ( $regex['revert_callback'] ) {
                    $translatedRegex = call_user_func( $regex['revert_callback'], $translatedRegex );
                }

                if ( $regex['type'] === SourceType::SOURCE_TEXT && $regex['source'] == $regex['text'] ) {
                    $source = preg_replace( '#\b' . preg_quote( $regex['source'], '#' ) . '\b#', $translatedRegex, $source );
                } else {
                    $source = str_replace( $regex['source_before_callback'], $translatedRegex, $source );
                }
            }
        }

        return $source;
    }


    public static function getSourceType( $source ) {
        if ( Text::isJSON( $source ) ) {
            return SourceType::SOURCE_JSON;
        } elseif ( Text::isHTML( $source ) ) {
            return SourceType::SOURCE_HTML;
        } else {
            return SourceType::SOURCE_TEXT;
        }
    }
}
