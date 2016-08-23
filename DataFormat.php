<?php

namespace RangelReale\dataformat;

use NumberFormatter;
use IntlDateFormatter;
use yii\helpers\FormatConverter;

use yii\base\InvalidParamException;
use yii\base\InvalidConfigException;

/**
 * Class DataFormat
 */
class DataFormat extends BaseDataFormat
{
    public $formatter;

    public $dateFormat = 'short';    
    public $timeFormat = 'short';
    public $timeperiodFormat = 'HH:mm:ss';
    public $datetimeFormat = 'short';
    
    private $_intlLoaded = false;
    
    public function init()
    {
        if (is_null($this->formatter))
            $this->formatter = \Yii::$app->formatter;
        else
            $this->formatter = \Yii::createObject($this->formatter);
        $this->_intlLoaded = extension_loaded('intl');
    }
    
    /**
     * @inheritdoc
     */
    public function asRaw($value)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asRaw($value);
    }

    /**
     * @inheritdoc
     */
    public function asText($value)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asText($value);
    }

    /**
     * @inheritdoc
     */
    public function asNtext($value)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asNtext($value);
    }

    /**
     * @inheritdoc
     */
    public function asParagraphs($value)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asParagraphs($value);
    }

    /**
     * @inheritdoc
     */
    public function asHtml($value, $config = null)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asHtml($value, $config);
    }

    /**
     * @inheritdoc
     */
    public function asEmail($value, $options = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asEmail($value, $options);
    }

    /**
     * @inheritdoc
     */
    public function asImage($value, $options = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asImage($value, $options);
    }

    /**
     * @inheritdoc
     */
    public function asUrl($value, $options = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asUrl($value, $options);
    }

    /**
     * @inheritdoc
     */
    public function asBoolean($value)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asBoolean($value);
    }

    /**
     * @inheritdoc
     */
    public function asDate($value, $format = null)
    {
        if ($value === null || $value == '') {
            return null;
        }
        if ($format === null) {
            $format = $this->dateFormat;
        }
        return $this->formatter->asDate($value, $format);
    }

    /**
     * @inheritdoc
     */
    public function asTime($value, $format = null)
    {
        if ($value === null || $value == '') {
            return null;
        }
        if ($format === null) {
            $format = $this->timeFormat;
        }
        return $this->formatter->asTime($value, $format);
    }
    
    /**
     * @inheritdoc
     */
    public function asTimeperiod($value, $format = null)
    {
        if ($value === null || $value === '') {
            return null;
        }
        if ($format === null) {
            $format = $this->timeperiodFormat;
        }
        
        $hours = intval(intval($value) / 3600);
        $minutes = intval(($value / 60) % 60);
        $seconds = intval($value % 60);
        $dt = mktime($hours, $minutes, $seconds, null, null, null);
        return $this->formatter->asTime($dt, $format);
    }

    /**
     * @inheritdoc
     */
    public function asDatetime($value, $format = null)
    {
        if ($value === null || $value == '') {
            return null;
        }
        if ($format === null) {
            $format = $this->datetimeFormat;
        }
        return $this->formatter->asDatetime($value, $format);
    }

    /**
     * @inheritdoc
     */
    public function asTimestamp($value)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asTimestamp($value);
    }

    /**
     * @inheritdoc
     */
    public function asRelativeTime($value, $referenceTime = null)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asRelativeTime($value, $referenceTime);
    }

    /**
     * @inheritdoc
     */
    public function asInteger($value, $options = [], $textOptions = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asInteger($value, $options, $textOptions);
    }

    /**
     * @inheritdoc
     */
    public function asDecimal($value, $decimals = null, $options = [], $textOptions = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asDecimal($value, $decimals, $options, $textOptions);
    }

    /**
     * @inheritdoc
     */
    public function asPercent($value, $decimals = null, $options = [], $textOptions = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asPercent($value, $decimals, $options, $textOptions);
    }

    /**
     * @inheritdoc
     */
    public function asScientific($value, $decimals = null, $options = [], $textOptions = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asScientific($value, $decimals, $options, $textOptions);
    }

    /**
     * @inheritdoc
     */
    public function asCurrency($value, $currency = null, $options = [], $textOptions = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asCurrency($value, $currency, $options, $textOptions);
    }

    /**
     * @inheritdoc
     */
    public function asSpellout($value)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asSpellout($value);
    }

    /**
     * @inheritdoc
     */
    public function asOrdinal($value)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asOrdinal($value);
    }

    /**
     * @inheritdoc
     */
    public function asShortSize($value, $decimals = null, $options = [], $textOptions = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asShortSize($value, $decimals, $options, $textOptions);
    }

    /**
     * @inheritdoc
     */
    public function asSize($value, $decimals = null, $options = [], $textOptions = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        return $this->formatter->asSize($value, $decimals, $decimals, $decimals);
    }
    
    /**
     * @inheritdoc
     */
    public function parseDate($value, $format = null)
    {
        if ($format === null) {
            $format = $this->dateFormat;
        }
        if ($format === null) {
            $format = $this->formatter->dateFormat;
        }
        return $this->parseDateTimeValue($value, $format, 'date');
    }    
    
    /**
     * @inheritdoc
     */
    public function parseTime($value, $format = null)
    {
        if ($format === null) {
            $format = $this->timeFormat;
        }
        if ($format === null) {
            $format = $this->formatter->timeFormat;
        }
        return $this->parseDateTimeValue($value, $format, 'time');
    }    

    /**
     * @inheritdoc
     */
    public function parseTimeperiod($value, $format = null)
    {
        if ($format === null) {
            $format = $this->timeperiodFormat;
        }
        $timeperiod = $this->parseDateTimeValue($value, $format, 'time');
        if ($timeperiod === null) {
            return null;
        }
        $dt=getdate($timeperiod);
        return $dt['seconds'] + ($dt['minutes'] * 60) + ($dt['hours'] * 60 * 60);
    }    
    
    /**
     * @inheritdoc
     */
    public function parseDatetime($value, $format = null)
    {
        if ($format === null) {
            $format = $this->datetimeFormat;
        }
        if ($format === null) {
            $format = $this->formatter->datetimeFormat;
        }
        return $this->parseDateTimeValue($value, $format, 'datetime');
    }    
    
    /**
     * @var array map of short format names to IntlDateFormatter constant values.
     */
    private $_dateFormats = [
        'short'  => 3, // IntlDateFormatter::SHORT,
        'medium' => 2, // IntlDateFormatter::MEDIUM,
        'long'   => 1, // IntlDateFormatter::LONG,
        'full'   => 0, // IntlDateFormatter::FULL,
    ];
    
    private function parseDateTimeValue($value, $format, $type)
    {
        if ($value === null || $value == '') {
            return null;
        }
        
        $timeZone = $this->formatter->timeZone;

        if ($this->_intlLoaded) {
            if (strncmp($format, 'php:', 4) === 0) {
                $format = FormatConverter::convertDatePhpToIcu(substr($format, 4));
            }
            
            if (isset($this->_dateFormats[$format])) {
                if ($type === 'date') {
                    $formatter = new IntlDateFormatter($this->formatter->locale, $this->_dateFormats[$format], IntlDateFormatter::NONE, $timeZone);
                } elseif ($type === 'time') {
                    $formatter = new IntlDateFormatter($this->formatter->locale, IntlDateFormatter::NONE, $this->_dateFormats[$format], $timeZone);
                } else {
                    $formatter = new IntlDateFormatter($this->formatter->locale, $this->_dateFormats[$format], $this->_dateFormats[$format], $timeZone);
                }
            } else {
                $formatter = new IntlDateFormatter($this->formatter->locale, IntlDateFormatter::NONE, IntlDateFormatter::NONE, $timeZone, null, $format);
            }
            if ($formatter === null) {
                throw new InvalidConfigException(intl_get_error_message());
            }
            if (($result = $formatter->parse($value)) === false) {
                throw new InvalidParamException('Parsing date/time value failed: ' . $formatter->getErrorCode() . ' ' . $formatter->getErrorMessage());
            }
            return $result;
        } else {
            throw new InvalidConfigException('Parse as Date/Time is only supported when PHP intl extension is installed.');
        }
    }    
    
    /**
     * @inheritdoc
     */
    public function parseInteger($value, $options = [], $textOptions = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        
        if ($this->_intlLoaded) {
            $f = $this->createNumberFormatter(NumberFormatter::DECIMAL, null, $options, $textOptions);
            $f->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
            if (($result = $f->parse($value, NumberFormatter::TYPE_INT64)) === false) {
                throw new InvalidParamException('Parsing integer value failed: ' . $f->getErrorCode() . ' ' . $f->getErrorMessage());
            }
            return (int)$result;
        } else {
            throw new InvalidConfigException('Parse as Decimal is only supported when PHP intl extension is installed.');
        }
        
        return $value;
    }    
    
    /**
     * @inheritdoc
     */
    public function parseDecimal($value, $decimals = null, $options = [], $textOptions = [])
    {
        if ($value === null || $value == '') {
            return null;
        }
        
        if ($this->_intlLoaded) {
            $f = $this->createNumberFormatter(NumberFormatter::DECIMAL, $decimals, $options, $textOptions);
            if (($result = $f->parse($value)) === false) {
                throw new InvalidParamException('Parsing decimal value failed: ' . $f->getErrorCode() . ' ' . $f->getErrorMessage());
            }
            return (float)$result;
        } else {
            throw new InvalidConfigException('Parse as Decimal is only supported when PHP intl extension is installed.');
        }
        
        return $value;
    }    
    
    protected function createNumberFormatter($style, $decimals = null, $options = [], $textOptions = [])
    {
        $formatter = new NumberFormatter($this->formatter->locale, $style);

        if ($this->formatter->decimalSeparator !== null) {
            $formatter->setSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL, $this->formatter->decimalSeparator);
        }
        if ($this->formatter->thousandSeparator !== null) {
            $formatter->setSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL, $this->formatter->thousandSeparator);
        }

        if ($decimals !== null) {
            $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $decimals);
            $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $decimals);
        }

        foreach ($this->formatter->numberFormatterTextOptions as $name => $attribute) {
            $formatter->setTextAttribute($name, $attribute);
        }
        foreach ($textOptions as $name => $attribute) {
            $formatter->setTextAttribute($name, $attribute);
        }
        foreach ($this->formatter->numberFormatterOptions as $name => $value) {
            $formatter->setAttribute($name, $value);
        }
        foreach ($options as $name => $value) {
            $formatter->setAttribute($name, $value);
        }
        foreach ($this->formatter->numberFormatterSymbols as $name => $symbol) {
            $formatter->setSymbol($name, $symbol);
        }
        return $formatter;
    }
}
