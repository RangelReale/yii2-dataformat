<?php

namespace RangelReale\dataformat;

use yii\base\Component;
use yii\base\InvalidParamException;

/**
 * Class BaseDataFormat
 */
class BaseDataFormat extends Component
{
    /**
     * Formats the value based on the given format type.
     * This method will call one of the "as" methods available in this class to do the formatting.
     * For type "xyz", the method "asXyz" will be used. For example, if the format is "html",
     * then [[asHtml()]] will be used. Format names are case insensitive.
     * @param mixed $value the value to be formatted.
     * @param string|array $format the format of the value, e.g., "html", "text". To specify additional
     * parameters of the formatting method, you may use an array. The first element of the array
     * specifies the format name, while the rest of the elements will be used as the parameters to the formatting
     * method. For example, a format of `['date', 'Y-m-d']` will cause the invocation of `asDate($value, 'Y-m-d')`.
     * @return string the formatting result.
     * @throws InvalidParamException if the format type is not supported by this class.
     */
    public function format($value, $format)
    {
        if (is_array($format)) {
            if (!isset($format[0])) {
                throw new InvalidParamException('The $format array must contain at least one element.');
            }
            $f = $format[0];
            $format[0] = $value;
            $params = $format;
            $format = $f;
        } else {
            $params = [$value];
        }
        $method = 'as' . $format;
        if ($this->hasMethod($method)) {
            return call_user_func_array([$this, $method], $params);
        } else {
            throw new InvalidParamException("Unknown format type: $format");
        }
    }

    /**
     * Formats the value as is without any formatting.
     * This method simply returns back the parameter without any format.
     * The only exception is a `null` value which will be formatted using [[nullDisplay]].
     * @param mixed $value the value to be formatted.
     * @return string the formatted result.
     */
    public function asRaw($value)
    {
        if ($value === null) {
            return null;
        }
        return $value;
    }

    /**
     * Formats the value as an HTML-encoded plain text.
     * @param string $value the value to be formatted.
     * @return string the formatted result.
     */
    public function asText($value)
    {
        if ($value === null) {
            return null;
        }
        return Html::encode($value);
    }

    /**
     * Formats the value as an HTML-encoded plain text with newlines converted into breaks.
     * @param string $value the value to be formatted.
     * @return string the formatted result.
     */
    public function asNtext($value)
    {
        if ($value === null) {
            return null;
        }
        return nl2br(Html::encode($value));
    }

    /**
     * Formats the value as HTML-encoded text paragraphs.
     * Each text paragraph is enclosed within a `<p>` tag.
     * One or multiple consecutive empty lines divide two paragraphs.
     * @param string $value the value to be formatted.
     * @return string the formatted result.
     */
    public function asParagraphs($value)
    {
        if ($value === null) {
            return null;
        }
        return str_replace('<p></p>', '', '<p>' . preg_replace('/\R{2,}/u', "</p>\n<p>", Html::encode($value)) . '</p>');
    }

    /**
     * Formats the value as HTML text.
     * The value will be purified using [[HtmlPurifier]] to avoid XSS attacks.
     * Use [[asRaw()]] if you do not want any purification of the value.
     * @param string $value the value to be formatted.
     * @param array|null $config the configuration for the HTMLPurifier class.
     * @return string the formatted result.
     */
    public function asHtml($value, $config = null)
    {
        if ($value === null) {
            return null;
        }
        return HtmlPurifier::process($value, $config);
    }

    /**
     * Formats the value as a mailto link.
     * @param string $value the value to be formatted.
     * @param array $options the tag options in terms of name-value pairs. See [[Html::mailto()]].
     * @return string the formatted result.
     */
    public function asEmail($value, $options = [])
    {
        if ($value === null) {
            return null;
        }
        return Html::mailto(Html::encode($value), $value, $options);
    }

    /**
     * Formats the value as an image tag.
     * @param mixed $value the value to be formatted.
     * @param array $options the tag options in terms of name-value pairs. See [[Html::img()]].
     * @return string the formatted result.
     */
    public function asImage($value, $options = [])
    {
        if ($value === null) {
            return null;
        }
        return Html::img($value, $options);
    }

    /**
     * Formats the value as a hyperlink.
     * @param mixed $value the value to be formatted.
     * @param array $options the tag options in terms of name-value pairs. See [[Html::a()]].
     * @return string the formatted result.
     */
    public function asUrl($value, $options = [])
    {
        if ($value === null) {
            return null;
        }
        $url = $value;
        if (strpos($url, '://') === false) {
            $url = 'http://' . $url;
        }

        return Html::a(Html::encode($value), $url, $options);
    }

    /**
     * Formats the value as a boolean.
     * @param mixed $value the value to be formatted.
     * @return string the formatted result.
     * @see booleanFormat
     */
    public function asBoolean($value)
    {
        return $value;
    }


    // date and time formats


    /**
     * Formats the value as a date.
     * @param integer|string|DateTime $value the value to be formatted. The following
     * types of value are supported:
     *
     * - an integer representing a UNIX timestamp
     * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
     *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
     * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object
     *
     * @param string $format the format used to convert the value into a date string.
     * If null, [[dateFormat]] will be used.
     *
     * This can be "short", "medium", "long", or "full", which represents a preset format of different lengths.
     * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime).
     *
     * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
     * PHP [date()](http://php.net/manual/de/function.date.php)-function.
     *
     * @return string the formatted result.
     * @throws InvalidParamException if the input value can not be evaluated as a date value.
     * @throws InvalidConfigException if the date format is invalid.
     * @see dateFormat
     */
    public function asDate($value, $format = null)
    {
        return $value;
    }

    /**
     * Formats the value as a time.
     * @param integer|string|DateTime $value the value to be formatted. The following
     * types of value are supported:
     *
     * - an integer representing a UNIX timestamp
     * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
     *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
     * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object
     *
     * @param string $format the format used to convert the value into a date string.
     * If null, [[timeFormat]] will be used.
     *
     * This can be "short", "medium", "long", or "full", which represents a preset format of different lengths.
     * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime).
     *
     * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
     * PHP [date()](http://php.net/manual/de/function.date.php)-function.
     *
     * @return string the formatted result.
     * @throws InvalidParamException if the input value can not be evaluated as a date value.
     * @throws InvalidConfigException if the date format is invalid.
     * @see timeFormat
     */
    public function asTime($value, $format = null)
    {
        return $value;
    }

    /**
     * Formats the value as a datetime.
     * @param integer|string|DateTime $value the value to be formatted. The following
     * types of value are supported:
     *
     * - an integer representing a UNIX timestamp
     * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
     *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
     * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object
     *
     * @param string $format the format used to convert the value into a date string.
     * If null, [[dateFormat]] will be used.
     *
     * This can be "short", "medium", "long", or "full", which represents a preset format of different lengths.
     * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime).
     *
     * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
     * PHP [date()](http://php.net/manual/de/function.date.php)-function.
     *
     * @return string the formatted result.
     * @throws InvalidParamException if the input value can not be evaluated as a date value.
     * @throws InvalidConfigException if the date format is invalid.
     * @see datetimeFormat
     */
    public function asDatetime($value, $format = null)
    {
        return $value;
    }

    /**
     * Formats a date, time or datetime in a float number as UNIX timestamp (seconds since 01-01-1970).
     * @param integer|string|DateTime $value the value to be formatted. The following
     * types of value are supported:
     *
     * - an integer representing a UNIX timestamp
     * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
     *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
     * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object
     *
     * @return string the formatted result.
     */
    public function asTimestamp($value)
    {
        return $value;
    }

    /**
     * Formats the value as the time interval between a date and now in human readable form.
     *
     * This method can be used in three different ways:
     *
     * 1. Using a timestamp that is relative to `now`.
     * 2. Using a timestamp that is relative to the `$referenceTime`.
     * 3. Using a `DateInterval` object.
     *
     * @param integer|string|DateTime|DateInterval $value the value to be formatted. The following
     * types of value are supported:
     *
     * - an integer representing a UNIX timestamp
     * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
     *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
     * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object
     * - a PHP DateInterval object (a positive time interval will refer to the past, a negative one to the future)
     *
     * @param integer|string|DateTime $referenceTime if specified the value is used as a reference time instead of `now`
     * when `$value` is not a `DateInterval` object.
     * @return string the formatted result.
     * @throws InvalidParamException if the input value can not be evaluated as a date value.
     */
    public function asRelativeTime($value, $referenceTime = null)
    {
        return $value;
    }

    /**
     * Formats the value as an integer number by removing any decimal digits without rounding.
     *
     * @param mixed $value the value to be formatted.
     * @param array $options optional configuration for the number formatter. This parameter will be merged with [[numberFormatterOptions]].
     * @param array $textOptions optional configuration for the number formatter. This parameter will be merged with [[numberFormatterTextOptions]].
     * @return string the formatted result.
     * @throws InvalidParamException if the input value is not numeric or the formatting failed.
     */
    public function asInteger($value, $options = [], $textOptions = [])
    {
        return $value;
    }

    /**
     * Formats the value as a decimal number.
     *
     * Property [[decimalSeparator]] will be used to represent the decimal point. The
     * value is rounded automatically to the defined decimal digits.
     *
     * @param mixed $value the value to be formatted.
     * @param integer $decimals the number of digits after the decimal point. If not given the number of digits is determined from the
     * [[locale]] and if the [PHP intl extension](http://php.net/manual/en/book.intl.php) is not available defaults to `2`.
     * @param array $options optional configuration for the number formatter. This parameter will be merged with [[numberFormatterOptions]].
     * @param array $textOptions optional configuration for the number formatter. This parameter will be merged with [[numberFormatterTextOptions]].
     * @return string the formatted result.
     * @throws InvalidParamException if the input value is not numeric or the formatting failed.
     * @see decimalSeparator
     * @see thousandSeparator
     */
    public function asDecimal($value, $decimals = null, $options = [], $textOptions = [])
    {
        return $value;
    }

    /**
     * Formats the value as a percent number with "%" sign.
     *
     * @param mixed $value the value to be formatted. It must be a factor e.g. `0.75` will result in `75%`.
     * @param integer $decimals the number of digits after the decimal point.
     * @param array $options optional configuration for the number formatter. This parameter will be merged with [[numberFormatterOptions]].
     * @param array $textOptions optional configuration for the number formatter. This parameter will be merged with [[numberFormatterTextOptions]].
     * @return string the formatted result.
     * @throws InvalidParamException if the input value is not numeric or the formatting failed.
     */
    public function asPercent($value, $decimals = null, $options = [], $textOptions = [])
    {
        return $value;
    }

    /**
     * Formats the value as a scientific number.
     *
     * @param mixed $value the value to be formatted.
     * @param integer $decimals the number of digits after the decimal point.
     * @param array $options optional configuration for the number formatter. This parameter will be merged with [[numberFormatterOptions]].
     * @param array $textOptions optional configuration for the number formatter. This parameter will be merged with [[numberFormatterTextOptions]].
     * @return string the formatted result.
     * @throws InvalidParamException if the input value is not numeric or the formatting failed.
     */
    public function asScientific($value, $decimals = null, $options = [], $textOptions = [])
    {
        return $value;
    }

    /**
     * Formats the value as a currency number.
     *
     * This function does not require the [PHP intl extension](http://php.net/manual/en/book.intl.php) to be installed
     * to work, but it is highly recommended to install it to get good formatting results.
     *
     * @param mixed $value the value to be formatted.
     * @param string $currency the 3-letter ISO 4217 currency code indicating the currency to use.
     * If null, [[currencyCode]] will be used.
     * @param array $options optional configuration for the number formatter. This parameter will be merged with [[numberFormatterOptions]].
     * @param array $textOptions optional configuration for the number formatter. This parameter will be merged with [[numberFormatterTextOptions]].
     * @return string the formatted result.
     * @throws InvalidParamException if the input value is not numeric or the formatting failed.
     * @throws InvalidConfigException if no currency is given and [[currencyCode]] is not defined.
     */
    public function asCurrency($value, $currency = null, $options = [], $textOptions = [])
    {
        return $value;
    }

    /**
     * Formats the value as a number spellout.
     *
     * This function requires the [PHP intl extension](http://php.net/manual/en/book.intl.php) to be installed.
     *
     * @param mixed $value the value to be formatted
     * @return string the formatted result.
     * @throws InvalidParamException if the input value is not numeric or the formatting failed.
     * @throws InvalidConfigException when the [PHP intl extension](http://php.net/manual/en/book.intl.php) is not available.
     */
    public function asSpellout($value)
    {
        return $value;
    }

    /**
     * Formats the value as a ordinal value of a number.
     *
     * This function requires the [PHP intl extension](http://php.net/manual/en/book.intl.php) to be installed.
     *
     * @param mixed $value the value to be formatted
     * @return string the formatted result.
     * @throws InvalidParamException if the input value is not numeric or the formatting failed.
     * @throws InvalidConfigException when the [PHP intl extension](http://php.net/manual/en/book.intl.php) is not available.
     */
    public function asOrdinal($value)
    {
        return $value;
    }

    /**
     * Formats the value in bytes as a size in human readable form for example `12 KB`.
     *
     * This is the short form of [[asSize]].
     *
     * If [[sizeFormatBase]] is 1024, [binary prefixes](http://en.wikipedia.org/wiki/Binary_prefix) (e.g. kibibyte/KiB, mebibyte/MiB, ...)
     * are used in the formatting result.
     *
     * @param integer $value value in bytes to be formatted.
     * @param integer $decimals the number of digits after the decimal point.
     * @param array $options optional configuration for the number formatter. This parameter will be merged with [[numberFormatterOptions]].
     * @param array $textOptions optional configuration for the number formatter. This parameter will be merged with [[numberFormatterTextOptions]].
     * @return string the formatted result.
     * @throws InvalidParamException if the input value is not numeric or the formatting failed.
     * @see sizeFormat
     * @see asSize
     */
    public function asShortSize($value, $decimals = null, $options = [], $textOptions = [])
    {
        return $value;
    }

    /**
     * Formats the value in bytes as a size in human readable form, for example `12 kilobytes`.
     *
     * If [[sizeFormatBase]] is 1024, [binary prefixes](http://en.wikipedia.org/wiki/Binary_prefix) (e.g. kibibyte/KiB, mebibyte/MiB, ...)
     * are used in the formatting result.
     *
     * @param integer $value value in bytes to be formatted.
     * @param integer $decimals the number of digits after the decimal point.
     * @param array $options optional configuration for the number formatter. This parameter will be merged with [[numberFormatterOptions]].
     * @param array $textOptions optional configuration for the number formatter. This parameter will be merged with [[numberFormatterTextOptions]].
     * @return string the formatted result.
     * @throws InvalidParamException if the input value is not numeric  or the formatting failed.
     * @see sizeFormat
     * @see asShortSize
     */
    public function asSize($value, $decimals = null, $options = [], $textOptions = [])
    {
        return $value;
    }
    
    
    /**
     * Parses the value based on the given format type.
     * This method will call one of the "parse" methods available in this class to do the formatting.
     * For type "xyz", the method "parseXyz" will be used. For example, if the format is "html",
     * then [[parseHtml()]] will be used. Format names are case insensitive.
     * @param mixed $value the value to be parsed.
     * @param string|array $format the format of the value, e.g., "html", "text". To specify additional
     * parameters of the parsing method, you may use an array. The first element of the array
     * specifies the format name, while the rest of the elements will be used as the parameters to the parsing
     * method. For example, a format of `['date', 'Y-m-d']` will cause the invocation of `parseDate($value, 'Y-m-d')`.
     * @return string the parsing result.
     * @throws InvalidParamException if the format type is not supported by this class.
     */
    public function parse($value, $format)
    {
        if (is_array($format)) {
            if (!isset($format[0])) {
                throw new InvalidParamException('The $format array must contain at least one element.');
            }
            $f = $format[0];
            $format[0] = $value;
            $params = $format;
            $format = $f;
        } else {
            $params = [$value];
        }
        $method = 'parse' . $format;
        if ($this->hasMethod($method)) {
            return call_user_func_array([$this, $method], $params);
        } else {
            throw new InvalidParamException("Unknown format type: $format");
        }
    }
 
    public function parseRaw($value)
    {
        return $value;
    }
    
    public function parseText($value)
    {
        return $value;
    }    
    
    public function parseNtext($value)
    {
        return $value;
    }    
    
    public function parseParagraphs($value)
    {
        return $value;
    }    
    
    public function parseHtml($value, $config = null)
    {
        return $value;
    }    
    
    public function parseEmail($value, $options = [])
    {
        return $value;
    }    
    
    public function parseImage($value, $options = [])
    {
        return $value;
    }    
    
    public function parseUrl($value, $options = [])
    {
        return $value;
    }    
    
    public function parseBoolean($value)
    {
        return $value;
    }    
    
    public function parseDate($value, $format = null)
    {
        return $value;
    }    
    
    public function parseTime($value, $format = null)
    {
        return $value;
    }    
    
    public function parseDatetime($value, $format = null)
    {
        return $value;
    }    
    
    public function parseTimestamp($value)
    {
        return $value;
    }    
    
    public function parseRelativeTime($value, $referenceTime = null)
    {
        return $value;
    }    
    
    public function parseInteger($value, $options = [], $textOptions = [])
    {
        return $value;
    }    
    
    public function parseDecimal($value, $decimals = null, $options = [], $textOptions = [])
    {
        return $value;
    }    
    
    public function parsePercent($value, $decimals = null, $options = [], $textOptions = [])
    {
        return $value;
    }    
    
    public function parseScientific($value, $decimals = null, $options = [], $textOptions = [])
    {
        return $value;
    }    
    
    public function parseCurrency($value, $currency = null, $options = [], $textOptions = [])
    {
        return $value;
    }    
    
    public function parseSpellout($value)
    {
        return $value;
    }    
    
    public function parseOrdinal($value)
    {
        return $value;
    }    
    
    public function parseShortSize($value, $decimals = null, $options = [], $textOptions = [])
    {
        return $value;
    }    
    
    public function parseSize($value, $decimals = null, $options = [], $textOptions = [])
    {
        return $value;
    }    
}
