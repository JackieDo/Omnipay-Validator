<?php namespace Omnipay\Validator\Traits;

/**
 * ValidatorMessageFormattersTrait
 *
 * @package omnipay-vnpay
 * @author Jackie Do <anhvudo@gmail.com>
 * @copyright 2018
 * @version $Id$
 * @access public
 */
trait ValidatorMessageFormattersTrait
{
    protected function formatInMessage($ruleParameter, $message)
    {
        $ruleParameter = $this->splitToArray($ruleParameter);

        $elements = array_reduce($ruleParameter, function($carry, $value) {
            $carry[] = $this->getValueDescription($value);
            return $carry;
        }, []);

        if (count($elements) > 2) {
            $lastElement = end($elements);
            array_pop($elements);

            $stringified = implode(', ', $elements) . ' and ' . $lastElement;
        } else {
            $stringified = implode(', ', $elements);
        }

        $message = str_replace(':list', $stringified, $message);

        return $message;
    }

    protected function formatEqualMessage($ruleParameter, $message)
    {
        return str_replace(':other', $ruleParameter, $message);
    }

    protected function formatMinMessage($ruleParameter, $message)
    {
        return str_replace(':min', $ruleParameter, $message);
    }

    protected function formatMaxMessage($ruleParameter, $message)
    {
        return str_replace(':max', $ruleParameter, $message);
    }

    protected function formatMinLengthMessage($ruleParameter, $message)
    {
        return $this->formatMinMessage($ruleParameter, $message);
    }

    protected function formatMaxLengthMessage($ruleParameter, $message)
    {
        return $this->formatMaxMessage($ruleParameter, $message);
    }

    protected function formatBetweenMessage($ruleParameter, $message)
    {
        $ruleParameter = $this->splitToArray($ruleParameter);

        $min = min($ruleParameter);
        $max = max($ruleParameter);

        return str_replace([':min', ':max'], [$min, $max], $message);
    }

    protected function formatBetweenLengthMessage($ruleParameter, $message)
    {
        return $this->formatBetweenMessage($ruleParameter, $message);
    }
}
