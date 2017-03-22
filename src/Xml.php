<?php

namespace Mingalevme\Utils;

class Xml extends \SimpleXMLElement
{
    /**
     * Shortcut for new \SimpleXMLElement($data, $options, <b>false</b>, $ns, $is_prefix)
     * 
     * @param string $data
     * @param int $options
     * @param string $ns
     * @param bool $is_prefix
     * @return static
     */
    public static function fromXml($data, $options = 0, $ns = "", $is_prefix = false)
    {
        return new static(static::safeize($data), $options, false, $ns, $is_prefix);
    }
    
    /**
     * Shortcut for new \SimpleXMLElement($data, $options, <b>true</b>, $ns, $is_prefix)
     * 
     * @param string $url
     * @param int $options
     * @param string $ns
     * @param bool $is_prefix
     * @return static
     */
    public static function fromUrl($url, $options = 0, $ns = "", $is_prefix = false)
    {
        return new static($url, $options, true, $ns, $is_prefix);
    }
    
    /**
     * Make UTF-8 XML string safe
     * 
     * @param type $xml
     * @return type
     */
    public static function safeize($xml)
    {
        return \preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
    }

    public function addChild($name, $value = null, $namespace = null, $CDATA = false)
    {
        if ($value && $CDATA) {
            $sxe = parent::addChild($name, null, $namespace);
            $node = dom_import_simplexml($sxe);
            $node->appendChild($node->ownerDocument->createCDATASection($value));
        } else {
            return parent::addChild($name, $value ? htmlspecialchars($value) : $value, $namespace);
        }
    }

    public function addAttribute($name, $value = null, $namespace = null)
    {
        return parent::addAttribute($name, $value ? htmlspecialchars($value) : $value, $namespace);
    }
    
    /**
     * Safely get element attribute value
     * 
     * @param string $attr Attribute name
     * @param string $default Default value if element or attribute doesn't exist
     * @return string
     */
    public function getAttr(string $attr, string $default=null)
    {
        if (isset($this) === false) {
            return $default;
        }
        
        try {
            if (isset($this->attributes()->{$attr}) === false) {
                return $default;
            }
        } catch (\ErrorException $e) {
            return $default;
        }

        return trim((string) $this->attributes()->{$attr});
    }
    
    /**
     * Get element value as text
     * 
     * @return string|null
     */
    public function asText()
    {
       if (isset($this)) {
           return trim($this);
       } else {
           return null;
       }
    }
    
    /**
     * Remove all unnecessary symbols
     * 
     * @return string
     */
    public function asCompressedXML()
    {
        return preg_replace('/>[\n\s\r]+?</', '><', $this->asXML());
    }
    
    /**
     * Get element child as string
     *
     * @param \SimpleXMLElement $el
     * @return string
     */
    public function getValue(string $child = null)
    {
        if (isset($this)) {
            if ($child && isset($this->{$child})) {
                return trim($this->{$child});
            } else {
                return trim($this);
            }
        }

        return null;
    }
    
    public function asXML($filename = null)
    {
        $xml = explode('?>', parent::asXML(), 2);
        
        if (count($xml) > 1) {
            $xml = trim($xml[1]);
        } else {
            $xml = trim($xml[0]);
        }
        
        if ($filename) {
            file_put_contents($filename, $xml);
        } else {
            return $xml;
        }
    }
}
