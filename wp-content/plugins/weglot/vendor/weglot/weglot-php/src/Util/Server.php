<?php

namespace Weglot\Util;

use Weglot\Client\Api\Enum\BotType;

/**
 * Class Server
 * @package Weglot\Parser\Util
 */
class Server
{
    /**
     * @param array $server
     * @param bool $use_forwarded_host
     * @return string
     */
    public static function fullUrl(array $server, $use_forwarded_host = false)
    {
        return self::urlOrigin($server, $use_forwarded_host) . $server['REQUEST_URI'];
    }

    /**
     * @param array $server
     * @param bool $use_forwarded_host
     * @return string
     */
    public static function urlOrigin(array $server, $use_forwarded_host = false)
    {
        return self::getProtocol($server) . '://' . self::getHost($server, $use_forwarded_host);
    }

    /**
     * @param array $server
     * @return bool
     */
    public static function detectBotVe(array $server)
    {
        $userAgent = self::getUserAgent($server);
        $checkBotVe = Text::contains($userAgent, 'Weglot Visual Editor');
        if($userAgent !== null && $checkBotVe){
            return true;
        }

        return false;
    }

    /**
     * @param array $server
     * @return int
     */
    public static function detectBot(array $server)
    {
        $userAgent = self::getUserAgent($server);
        $checkBotAgent = preg_match('/bot|favicon|crawl|facebook|slurp|spider/i', $userAgent);
        $checkBotGoogle = (Text::contains($userAgent, 'Google') ||
                            Text::contains($userAgent, 'facebook') ||
                            Text::contains($userAgent, 'wprocketbot') ||
                            Text::contains($userAgent, 'Ahrefs') ||
                            Text::contains($userAgent, 'SemrushBot'));

        if ($userAgent !== null && !$checkBotAgent) {
            return BotType::HUMAN;
        }
        if ($userAgent !== null && $checkBotAgent && $checkBotGoogle) {
            return BotType::GOOGLE;
        }
        foreach (self::otherBotAgents() as $agent => $agentBot) {
            if ($userAgent !== null && $checkBotAgent && !$checkBotGoogle && Text::contains($userAgent, $agent)) {
                return $agentBot;
            }
        }

        return BotType::OTHER;
    }

    /**
     * @return array
     */
    private static function otherBotAgents()
    {
        return [
            'bing' => BotType::BING,
            'yahoo' => BotType::YAHOO,
            'Baidu' => BotType::BAIDU,
            'Yandex' => BotType::YANDEX
        ];
    }

    /**
     * @param array $server
     * @return bool
     */
    private static function isSsl(array $server)
    {
        if ( isset($server['HTTPS']) ) {
            if ( 'on' == strtolower($server['HTTPS']) ) {
                return true;
            }

            if ( '1' == $server['HTTPS'] ){
                return true;
            }
	        elseif ( isset($server['SERVER_PORT']) && ( '443' == $server['SERVER_PORT'] ) ) {
                return true;
            }
        }

        if( isset( $server['HTTP_X_FORWARDED_PROTO'] ) && 'https' === $server['HTTP_X_FORWARDED_PROTO'] ){
            return true;
        }

        return false;
    }

    /**
     * @param array $server
     * @return string
     */
    public static function getProtocol(array $server)
    {
        $protocol = strtolower($server['SERVER_PROTOCOL']);
        return substr($protocol, 0, strpos($protocol, '/')) . (self::isSsl($server) ? 's' : '');
    }

    /**
     * @param array $server
     * @return string
     */
    public static function getPortForUrl(array $server)
    {
        $ssl = self::isSsl($server);

        if ((!$ssl && self::getPort($server) === '80') ||
            ($ssl && self::getPort($server) === '443')) {
            return '';
        }
        return ':' . self::getPort($server);
    }

    /**
     * @param array $server
     * @return string
     */
    public static function getPort(array $server)
    {
        if (!isset($server['SERVER_PORT'])) {
            return '';
        }
        return $server['SERVER_PORT'];
    }

    /**
     * @param array $server
     * @param bool $use_forwarded_host
     * @return string
     */
    public static function getHost(array $server, $use_forwarded_host = false)
    {
        $host = null;

        if ($use_forwarded_host && isset($server['HTTP_X_FORWARDED_HOST'])) {
            $host = $server['HTTP_X_FORWARDED_HOST'];
        } elseif (isset($server['HTTP_HOST'])) {
            $host = $server['HTTP_HOST'];
        }

        if ($host === null && isset($server['SERVER_NAME'])) {
            $host = $server['SERVER_NAME'] . self::getPort($server);
        }

        return $host;
    }

    /**
     * @param array $server
     * @return string|null
     */
    public static function getUserAgent(array $server)
    {
        return isset($server['HTTP_USER_AGENT']) ? $server['HTTP_USER_AGENT'] : null;
    }
}
