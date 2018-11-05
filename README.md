# WP_Metaful

A simple wrapper around the WordPress function get_post_meta() allowing you to retrieve all the post meta for a given post within one query and easily output different fields using a simple and intuitive API.

## ACF Integration

This wrapper has also been tightly coupled with Advanced Custom Fields in some places as this is a plugin that I regularly use on projects. Some examples of this are in the examples below.

## Database Queries

The original drive for creating this was that calling get_post_meta($post_id, 'some_field', true) each time you want to output a field creates an additional database query with every call and using ACFs functions get_field() & the_field() for example can generate more than one query per field which on pages with a large amount of post meta can really add up. 

Using WP_Metaful will only generate 1 query for all pieces of post meta drastically reducing the numbers of queries you might have on a page.

## Examples

### Set up:
The below example will collect all the post meta for the current post.
```php
$meta = new WP_Metaful;
```
Or if you would like to get the post meta for another post id simply pass the post id when newing up the class like so.
```php
$meta = new WP_Metaful($post_id);
```
You are now free to call the API methods as required.

### Standard field
```php
echo $meta->field('my_field');
```
By default the output is escaped. You can set this to false by passing a second parameter of false.

### Standard field
```php
echo $meta->field('my_field', false);
```

### ACF Image field
```php
echo $meta->image('media_post_id');
```
You can specify the size of the image in the second parameter if required.
```php
echo $meta->image('media_post_id', 'your-thumbnail-size');
```

### ACF Image field. Output URL to image
```php
echo $meta->imageSrc('media_post_id');
```
Again, you can specify the size of the image in the second parameter if required.
```php
echo $meta->imageSrc('media_post_id', 'your-thumbnail-size');
```

### ACF Page Link field
```php
echo $meta->link('post_id');
```

### Date field
```php
// Optional params
public function date($field, $format = 'D m Y', $createFromFormat = 'Y-m-d H:i:s');

// Usage
echo $meta->date('date_field');
```

### ACF Post Object
```php
$postObject = $meta->postObject('post_id');
```

### ACF Repeater
Basic usage. Always pass the $key once you are inside the loop so that we can lookup the correct field name at he correct point in the repeater.
```php
<?php if ($repeater = $meta->repeater('repeater_field')): ?>
	
	<div>

		<?php foreach ($repeater as $key => $value): ?>

			<p><?php echo $meta->repeaterItem('sub_field_key', $key); ?></p>

		<?php endforeach ?>

	</div>

<?php endif ?>
```
#### Advanced Repeater Usage:

##### $meta->repeaterKey()

If you would like to use the other API methods for outputting specific field types but are inside a repeater, you can do so with the below example. We will use the image field for this example.
```php
echo $meta->image($meta->repeaterKey('media_post_id', $key));
```

##### $meta->getRepeaterName()

The above is a protected method, however it can be utilised by passing a third parmeter to the repeaterItem() or repeaterKey() methods. 'Why' you ask? This is a bit of an edge case but if you are not in a foreach loop but would like to output a piece of meta that is inside a repeater field you can also specifiy this as per the below example:
```php
$secondItemInTheRepeater = $meta->repeaterItem('sub_field_key', 2, 'repeater_name');
```

### Debugging
The below outputs all the post meta as an array.
```php
$meta->all();
```
## TODO

I'd still like to add ACF Flexible Content Field support but the opportunity hasn't come up yet. If you have any other ideas feel free to reach out!
