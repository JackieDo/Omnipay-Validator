<?php namespace Omnipay\Validator\Traits;

/**
 * ValidatorRulesTrait
 *
 * @package omnipay-validator
 * @author Jackie Do <anhvudo@gmail.com>
 * @copyright 2018
 * @version $Id$
 * @access public
 */
trait ValidatorRulesTrait
{
    protected function checkIsset($value, $comparison)
    {
        return $comparison && isset($value);
    }

    protected function checkRequired($value, $comparison)
    {
        if ($comparison && is_null($value)) {
            return false;
        }

        if ($comparison && is_string($value) && trim($value) === '') {
            return false;
        }

        return true;
    }

    protected function checkNumeric($value, $comparison)
    {
        return $comparison && is_numeric($value);
    }

    protected function checkDigits($value, $comparison)
    {
        return $comparison && preg_match('/^[\d]+$/', $value);
    }

    protected function checkIsoLatinAlpha($value, $comparison)
    {
        return $comparison && is_string($value) && preg_match('/^[a-zA-Z]+$/', $value);
    }

    protected function checkIsoLatinAlphaNum($value, $comparison)
    {
        return $comparison && (is_string($value) || is_numeric($value)) && preg_match('/^[a-zA-Z0-9]+$/', $value);
    }

    protected function checkIsoLatinAlphaDash($value, $comparison)
    {
        return $comparison && (is_string($value) || is_numeric($value)) && preg_match('/^[a-zA-Z0-9_-]+$/', $value);
    }

    protected function checkIsoLatinAlphaSpace($value, $comparison)
    {
        return $comparison && is_string($value) && preg_match('/^[a-zA-Z\s]+$/', $value);
    }

    protected function checkIsoLatinAlphaNumSpace($value, $comparison)
    {
        return $comparison && (is_string($value) || is_numeric($value)) && preg_match('/^[a-zA-Z0-9\s]+$/', $value);
    }

    protected function checkIsoLatinAlphaDashSpace($value, $comparison)
    {
        return $comparison && (is_string($value) || is_numeric($value)) && preg_match('/^[a-zA-Z0-9\s_-]+$/', $value);
    }

    protected function checkAlpha($value, $comparison)
    {
        return $comparison && is_string($value) && preg_match('/^[\pL\pM]+$/u', $value);
    }

    protected function checkAlphaNum($value, $comparison)
    {
        return $comparison && (is_string($value) || is_numeric($value)) && preg_match('/^[\pL\pM\pN]+$/u', $value);
    }

    protected function checkAlphaDash($value, $comparison)
    {
        return $comparison && (is_string($value) || is_numeric($value)) && preg_match('/^[\pL\pM\pN_-]+$/u', $value);
    }

    protected function checkAlphaSpace($value, $comparison)
    {
        return $comparison && is_string($value) && preg_match('/^[\pL\pM\s]+$/u', $value);
    }

    protected function checkAlphaNumSpace($value, $comparison)
    {
        return $comparison && (is_string($value) || is_numeric($value)) && preg_match('/^[\pL\pM\pN\s]+$/u', $value);
    }

    protected function checkAlphaDashSpace($value, $comparison)
    {
        return $comparison && (is_string($value) || is_numeric($value)) && preg_match('/^[\pL\pM\pN\s_-]+$/u', $value);
    }

    protected function checkIn($value, $comparison)
    {
        $comparison = $this->splitToArray($comparison);

        return in_array($value, $comparison, true);
    }

    protected function checkEqual($value, $comparison)
    {
        return $value == $comparison;
    }

    protected function checkMin($value, $comparison)
    {
        return is_numeric($value) && $value >= $comparison;
    }

    protected function checkMax($value, $comparison)
    {
        return is_numeric($value) && $value <= $comparison;
    }

    protected function checkMinLength($value, $comparison)
    {
        return (is_string($value) || is_numeric($value)) && mb_strlen($value) >= $comparison;
    }

    protected function checkMaxLength($value, $comparison)
    {
        return (is_string($value) || is_numeric($value)) && mb_strlen($value) <= $comparison;
    }

    protected function checkBetween($value, $comparison)
    {
        $comparison = $this->splitToArray($comparison);

        if (count($comparison) > 0) {
            $min = min($comparison);
            $max = max($comparison);

            return is_numeric($value) && $value >= $min && $value <= $max;
        }

        return false;
    }

    protected function checkBetweenLength($value, $comparison)
    {
        $comparison = $this->splitToArray($comparison);

        if (count($comparison) > 0) {
            $min = min($comparison);
            $max = max($comparison);

            return (is_string($value) || is_numeric($value)) && mb_strlen($value) >= $min && mb_strlen($value) <= $max;
        }

        return false;
    }

    protected function checkEmail($value, $comparison)
    {
        return $comparison && (filter_var($value, FILTER_VALIDATE_EMAIL) !== false);
    }

    protected function checkBoolean($value, $comparison)
    {
        $acceptable = [true, false, 0, 1, '0', '1'];

        return $comparison && in_array($value, $acceptable, true);
    }

    protected function checkInteger($value, $comparison)
    {
        return $comparison && (filter_var($value, FILTER_VALIDATE_INT) !== false);
    }

    protected function checkIp($value, $comparison)
    {
        return $comparison && (filter_var($value, FILTER_VALIDATE_IP) !== false);
    }

    protected function checkIpv4($value, $comparison)
    {
        return $comparison && (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false);
    }

    protected function checkIpv6($value, $comparison)
    {
        return $comparison && (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false);
    }

    protected function checkUrl($value, $comparison)
    {
        $pattern = '~^
            ((aaa|aaas|about|acap|acct|acr|adiumxtra|afp|afs|aim|apt|attachment|aw|barion|beshare|bitcoin|blob|bolo|callto|cap|chrome|chrome-extension|cid|coap|coaps|com-eventbrite-attendee|content|crid|cvs|data|dav|dict|dlna-playcontainer|dlna-playsingle|dns|dntp|dtn|dvb|ed2k|example|facetime|fax|feed|feedready|file|filesystem|finger|fish|ftp|geo|gg|git|gizmoproject|go|gopher|gtalk|h323|ham|hcp|http|https|iax|icap|icon|im|imap|info|iotdisco|ipn|ipp|ipps|irc|irc6|ircs|iris|iris.beep|iris.lwz|iris.xpc|iris.xpcs|itms|jabber|jar|jms|keyparc|lastfm|ldap|ldaps|magnet|mailserver|mailto|maps|market|message|mid|mms|modem|ms-help|ms-settings|ms-settings-airplanemode|ms-settings-bluetooth|ms-settings-camera|ms-settings-cellular|ms-settings-cloudstorage|ms-settings-emailandaccounts|ms-settings-language|ms-settings-location|ms-settings-lock|ms-settings-nfctransactions|ms-settings-notifications|ms-settings-power|ms-settings-privacy|ms-settings-proximity|ms-settings-screenrotation|ms-settings-wifi|ms-settings-workplace|msnim|msrp|msrps|mtqp|mumble|mupdate|mvn|news|nfs|ni|nih|nntp|notes|oid|opaquelocktoken|pack|palm|paparazzi|pkcs11|platform|pop|pres|prospero|proxy|psyc|query|redis|rediss|reload|res|resource|rmi|rsync|rtmfp|rtmp|rtsp|rtsps|rtspu|secondlife|service|session|sftp|sgn|shttp|sieve|sip|sips|skype|smb|sms|smtp|snews|snmp|soap.beep|soap.beeps|soldat|spotify|ssh|steam|stun|stuns|submit|svn|tag|teamspeak|tel|teliaeid|telnet|tftp|things|thismessage|tip|tn3270|turn|turns|tv|udp|unreal|urn|ut2004|vemmi|ventrilo|videotex|view-source|wais|webcal|ws|wss|wtai|wyciwyg|xcon|xcon-userid|xfire|xmlrpc\.beep|xmlrpc.beeps|xmpp|xri|ymsgr|z39\.50|z39\.50r|z39\.50s))://                                 # protocol
            (([\pL\pN-]+:)?([\pL\pN-]+)@)?          # basic auth
            (
                ([\pL\pN\pS-\.])+(\.?([\pL]|xn\-\-[\pL\pN-]+)+\.?) # a domain name
                    |                                              # or
                \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}                 # an IP address
                    |                                              # or
                \[
                    (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
                \]  # an IPv6 address
            )
            (:[0-9]+)?                              # a port (optional)
            (/?|/\S+|\?\S*|\#\S*)                   # a /, nothing, a / with something, a query or a fragment
        $~ixu';

        return $comparison && is_string($value) && preg_match($pattern, $value);
    }

    protected function checkRegex($value, $comparison)
    {
        return (is_string($value) || is_numeric($value)) && preg_match($comparison, $value);
    }
}
