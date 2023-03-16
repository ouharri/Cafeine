<?php

namespace Weglot\Parser\Formatter;

use WGSimpleHtmlDom\simple_html_dom;
use Weglot\Parser\Parser;

class ExcludeBlocksFormatter {
    /**
     * @var simple_html_dom
     */
    protected $dom;

    /**
     * @var array
     */
    protected $excludeBlocks;

    /**
     * @var array
     */
    protected $whiteList;

    /**
     * ExcludeBlocksFormatter constructor.
     *
     * @param $dom
     */
    public function __construct( $dom, $excludeBlocks, $whiteList = [] ) {
        $this
            ->setDom( $dom )
            ->setExcludeBlocks( $excludeBlocks )
            ->setWhiteList( $whiteList );
        $this->handle();
    }

    /**
     * @param simple_html_dom $dom
     *
     * @return $this
     */
    public function setDom( simple_html_dom $dom ) {
        $this->dom = $dom;

        return $this;
    }

    /**
     * @return simple_html_dom
     */
    public function getDom() {
        return $this->dom;
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
     * Add ATTRIBUTE_NO_TRANSLATE to dom elements that don't
     * wanna be translated or ATTRIBUTE_TRANSLATE if on mode
     * wg-mode-whitelist
     *
     * @return void
     */
    public function handle() {
        if ( ! empty( $this->whiteList ) ) {
            foreach ( $this->whiteList as $exception ) {
                foreach ( $this->dom->find( $exception ) as $k => $row ) {
                    $attribute       = Parser::ATTRIBUTE_TRANSLATE;
                    $row->$attribute = '';
                }
            }

        } else {
            foreach ( $this->excludeBlocks as $exception ) {
                foreach ( $this->dom->find( $exception ) as $k => $row ) {
                    $attribute       = Parser::ATTRIBUTE_NO_TRANSLATE;
                    $row->$attribute = '';
                }
            }
        }
    }
}
