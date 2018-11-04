<?php

class WP_Metaful
{
    protected $postMeta;

    protected $repeaterName;

    /**
     * Set up WP_Metaful with either the current post id or the given one
     * 
     * @param integer $post_id
     */
    public function __construct($post_id = null)
    {
        if (is_null($post_id)) 
        {
            $post_id = get_the_id();
        }

        $this->postMeta = get_post_meta($post_id);
    }

    /**
     * Return the value of the given meta key. If you are expecting an array returned
     * set $escape to false and escape manually each array item.
     * 
     * @param  string  $field
     * @param  boolean $escape
     * @return mixed
     */
    public function field($field, $escape = true)
    {
        if (isset($this->postMeta[$field][0])) 
        {
            return $this->prepareReturn($this->postMeta[$field][0], $escape);
        }
        
        return false;
    }

    /**
     * Get the post thumbnail for the meta keys returned post id
     * 
     * @param  string $field
     * @param  string $size
     * @return html|false
     */
    public function image($field, $size = 'full')
    {
        if ($this->field($field)) 
        {
            return wp_get_attachment_image($this->field($field), $size);
        }

        return false;
    }

    /**
     * Get the post thumbnail url for the meta keys returned post id
     * 
     * @param  string $field
     * @param  string $size
     * @return string|false
     */
    public function imageSrc($field, $size = 'full')
    {
        if ($this->field($field)) 
        {
            return wp_get_attachment_image_src($this->field($field), $size)[0];
        }

        return false;
    }

    /**
     * Generate a link for a given fields returned id. (Post id)
     * 
     * @param  string $field key
     * @return string|false
     */
    public function link($field)
    {
        if ($this->field($field)) 
        {
            return get_permalink($this->field($field));
        }

        return false;
    }

    /**
     * Easily Output date meta in a nice format.
     * 
     * @param  string $field
     * @param  string $format | Date format to return
     * @param  string $createFromFormat | Format to create the DateTime Instance from
     * @return string
     */
    public function date($field, $format = 'D m Y', $createFromFormat = 'Y-m-d H:i:s')
    {
        if ($this->field($field)) 
        {
            $date = DateTime::createFromFormat($createFromFormat, $this->field($field));
            
            return $date->format($format);
        }

        return false;
    }

    /**
     * ACF post object field. Gets the id then create a post object.
     * If you don't want the post object just use the field()
     * method instead.
     * 
     * @param  string $field
     * @return WP_Post
     */
    public function postObject($field)
    {
        if ($this->field($field)) 
        {
            return get_post($this->field($field));
        }

        return false;
    }

    /**
     * Return the ACF repeater count as an array so that we can loop through it.
     * 
     * @param  string $field Key of acf repeater
     * @return array|false
     */
    public function repeater($field)
    {
        if ($this->field($field)) 
        {
            $this->repeaterName = $field;

            $count = $this->field($field);

            return array_fill(0, $count, $field);
        }

        return false;
    }

    /**
     * Display a repeater item from inside the repeater
     * 
     * @param  string $field
     * @param  integer $item_key
     * @param  string $repeaterName
     * @return WP_Metaful->field()
     */
    public function repeaterItem($field, $item_key, $repeaterName = null)
    {
        return $this->field($this->repeaterKey($field, $item_key, $repeaterName));
    }

    /**
     * Get the repeater key. Useful for ouputing different types of fields inside of a repeater
     * 
     * @param  string $field
     * @param  integer $item_key
     * @param  string $repeaterName
     * @return string
     */
    public function repeaterKey($field, $item_key, $repeaterName = null)
    {
        return $this->getRepeaterName($repeaterName) . '_' . $item_key . '_' . $field;
    }

    /**
     * Private method that allows the overriding on the repeaterItem() method 
     * the repeaterName if required. If not we will retrieve the containing 
     * repeaterName field if it was set when initialising the repeater.
     * 
     * @param  string $repeaterName
     * @return string
     */
    protected function getRepeaterName($repeaterName = null)
    {
        if (!is_null($repeaterName)) 
        {
            return $repeaterName;
        }

        return $this->repeaterName;
    }

    /**
     * Return either escaped or not escaped data
     * 
     * @param  string  $value
     * @param  boolean $escape
     * @return string
     */
    protected function shouldEscape($value, $escape = true)
    {
        if ($escape) 
        {
            return esc_html($value);
        }

        return $value;
    }

    /**
     * Prepare to return. Checks for serialisation. If expected data is serialised, 
     * $escape must be set to false and escape manually on each return array
     * item.
     * 
     * @param  string $value
     * @param  boolean $escape
     * @return WP_Metaful shouldEscape
     */
    protected function prepareReturn($value, $escape)
    {
        return $this->shouldEscape(maybe_unserialize($value), $escape);
    }

    /**
     * Get all fields. Useful for debugging. 
     * 
     * @return array
     */
    public function all()
    {
        return $this->postMeta;
    }
}
