<?php
/**
 * @author Remy Berda
 * User: remy
 * Date: 12/06/2019
 * Time: 16:52
 */

namespace Weglot\Parser\Check\Regex;

use Weglot\Parser\Parser;


/**
 * Class RegexChecker
 * @package Weglot\Parser\Check
 */
class RegexChecker
{
    /**
     * DOM node to match
     *
     * @var string
     */
    public $regex = '';

    /**
     * DOM node to match
     *
     * @var string
     */
    public $type = '';


    public $var_number = 1;


    /**
     * DOM node to match
     *
     * @var string
     */
    public $keys = '';


    public $callback = null;
    public $revert_callback = null;



    /**
     * DomChecker constructor.
     * @param Parser $parser
     */
    public function __construct($regex = '' , $type = '' , $var_number = 0 , $keys = array(), $callback = null, $revert_callback = null )
    {
        $this->regex        = $regex;
        $this->type         =  $type;
        $this->var_number   = $var_number;
        $this->keys         = $keys;
        $this->callback     = $callback;
        $this->revert_callback     = $revert_callback;
    }


    /**
     * @return array
     */

    public function toArray()
    {
        return [
            $this->regex,
            $this->type,
            $this->var_number,
            $this->keys,
            $this->callback,
            $this->revert_callback
        ];
    }
}