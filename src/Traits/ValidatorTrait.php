<?php namespace Omnipay\Validator\Traits;

use Omnipay\Common\Exception\BadMethodCallException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Helper;

/**
 * ValidatorTrait
 *
 * @package omnipay-validator
 * @author Jackie Do <anhvudo@gmail.com>
 * @copyright 2018
 * @version $Id$
 * @access public
 */
trait ValidatorTrait
{
    use ValidatorRulesTrait, ValidatorMessageFormattersTrait;

    /**
     * Default validate messages
     *
     * @var array
     */
    protected $validateMessages = [
        'default'                    => 'The :parameter parameter is invalid.',
        'isset'                      => 'The :parameter parameter is required',
        'required'                   => 'The :parameter parameter should be assigned a value.',
        'numeric'                    => 'The :parameter parameter must be a numeric.',
        'digits'                     => 'The :parameter parameter must be entirely digit characters.',
        'alpha'                      => 'The :parameter parameter may only contain letters.',
        'alpha_num'                  => 'The :parameter parameter may only contain letters and numbers.',
        'alpha_dash'                 => 'The :parameter parameter may only contain letters, numbers, dashes and underscores.',
        'alpha_space'                => 'The :parameter parameter may only contain letters and whitespace.',
        'alpha_num_space'            => 'The :parameter parameter may only contain letters, numbers and whitespace.',
        'alpha_dash_space'           => 'The :parameter parameter may only contain letters, numbers, dashes, underscores and whitespace.',
        'iso_latin_alpha'            => 'The :parameter parameter may only contain iso-latin letters.',
        'iso_latin_alpha_num'        => 'The :parameter parameter may only contain iso-latin letters and numbers.',
        'iso_latin_alpha_dash'       => 'The :parameter parameter may only contain iso-latin letters, numbers, dashes and underscores.',
        'iso_latin_alpha_space'      => 'The :parameter parameter may only contain iso-latin letters and whitespace.',
        'iso_latin_alpha_num_space'  => 'The :parameter parameter may only contain iso-latin letters, numbers and whitespace.',
        'iso_latin_alpha_dash_space' => 'The :parameter parameter may only contain iso-latin letters, numbers, dashes, underscores and whitespace.',
        'in'                         => 'The :parameter parameter only accept one of the following values: :list.',
        'equal'                      => 'The :parameter parameter must be equal to :other.',
        'min'                        => 'The :parameter parameter must be at least :min.',
        'max'                        => 'The :parameter parameter may not be greater than :max.',
        'min_length'                 => 'The :parameter parameter must be at least :min characters.',
        'max_lenth'                  => 'The :parameter parameter may not be greater than :max characters.',
        'between'                    => 'The :parameter parameter must be between :min and :max.',
        'between_length'             => 'The :parameter parameter must be between :min and :max characters.',
        'email'                      => 'The :parameter parameter must be a valid email address.',
        'regex'                      => 'The :parameter parameter format is invalid.',
        'boolean'                    => 'The :parameter parameter field must be true or false.',
        'integer'                    => 'The :parameter parameter must be an integer.',
        'ip'                         => 'The :parameter parameter must be a valid IP address.',
        'ipv4'                       => 'The :parameter parameter must be a valid IPv4 address.',
        'ipv6'                       => 'The :parameter parameter must be a valid IPv6 address.',
        'url'                        => 'The :parameter parameter must be an URL format.',
     ];

    /**
     * Validate all parameters of request with defined rules.
     *
     * This method is called internally by gateways to avoid wasting time with an API call
     * when the request is clearly invalid.
     *
     * @param  array  $validateRules
     * @param  array  $validateMessages
     * @param  array  $parametricConverter
     * @throws InvalidRequestException
     * @throws BadMethodCallException
     */
    public function validateWithRules(array $validateRules, array $validateMessages = [], array $parametricConverter = [])
    {
        $this->validateDataWithRules($this->getParameters(), $validateRules, $validateMessages, $parametricConverter);
    }

    /**
     * Validate data with defined rules
     *
     * @param  array  $data
     * @param  array  $validateRules
     * @param  array  $validateMessages
     * @param  array  $parametricConverter
     * @throws InvalidRequestException
     * @throws BadMethodCallException
     */
    public function validateDataWithRules(array $data, array $validateRules, array $validateMessages = [], array $parametricConverter = [])
    {
        foreach ($validateRules as $key => $rules) {
            $value = array_key_exists($key, $data) ? $data[$key] : null;

            if (array_key_exists('nullable', $rules) && $rules['nullable'] && is_null($value)) {
                continue;
            }

            unset($rules['nullable']);

            $defaultParametricConverter = (method_exists($this, 'getParametricConverter') && is_array($this->getParametricConverter())) ? $this->getParametricConverter() : [];
            $parametricConverter = array_merge($defaultParametricConverter, $parametricConverter);
            $parameter = (array_key_exists($key, $parametricConverter)) ? $parametricConverter[$key] : $key;

            foreach ($rules as $ruleName => $ruleParameter) {
                if ($ruleName === 'callback') {
                    if (!is_callable($ruleParameter)) {
                        throw new BadMethodCallException('The reference of rule named `callback` must be a valid callable. You provided ' . $this->getValueDescription($ruleParameter) . '.');
                    }

                    call_user_func($ruleParameter, $value, InvalidRequestException::class);
                } else {
                    $method = 'check'.ucfirst(Helper::camelCase($ruleName));

                    if (!method_exists($this, $method)) {
                        throw new BadMethodCallException('Call to undefined the ' .get_class($this). '::' .$method. '() validator that associated with the `' .$ruleName. '` rule');
                    }

                    if (!$this->$method($value, $ruleParameter)) {
                        $message = isset($validateMessages[$key][$ruleName]) ? $validateMessages[$key][$ruleName] : null;

                        throw new InvalidRequestException($this->formatMessage($parameter, $ruleName, $ruleParameter, $message));
                    }
                }
            }
        }
    }

    /**
     * Format message for special rule
     *
     * @param  string $parameter
     * @param  string $ruleName
     * @param  mixed  $ruleParameter
     * @param  string $customMessage
     *
     * @return string
     */
    protected function formatMessage($parameter, $ruleName, $ruleParameter, $message = null)
    {
        $message = $message ?: (isset($this->validateMessages[$ruleName]) ? $this->validateMessages[$ruleName] : $this->validateMessages['default']);
        $message = str_replace(':parameter', $parameter, $message);
        $method  = 'format'.ucfirst(Helper::camelCase($ruleName)).'Message';

        if (method_exists($this, $method)) {
            return $this->$method($ruleParameter, $message);
        }

        return $message;
    }

    /**
     * Get description of value
     *
     * @param  mixed $value
     *
     * @return string
     */
    protected function getValueDescription($value)
    {
        switch (true) {
            case (is_numeric($value)):
                $value = strval($value);
                break;

            case (is_bool($value) && $value):
                $value = '(boolean) true';
                break;

            case (is_bool($value) && !$value):
                $value = '(boolean) false';
                break;

            case (is_null($value)):
                $value = 'null value';
                break;

            default:
                break;
        }

        return $value;
    }

    /**
     * Split a string into array using comma
     *
     * @param  mixed $value
     *
     * @return array
     */
    protected function splitToArray($value)
    {
        if (is_array($value)) {
            return $value;
        }

        return preg_split('/\s*\,\s*/', $comparison);
    }
}
