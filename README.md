# WP_Metaful

A simple wrapper around the WordPress function get_post_meta() allowing us to retrieve all the post meta for a given post within one query and easily output different fields using a simple and intuitive API.

## ACF Integration

This wrapper has also been tightly coupled with Advanced Custom Fields in some places as this is a plugin that I regularly use on projects. Some examples of this are in the examples below.

## Database Queries

The original drive for creating this was that calling get_post_meta($post_id, 'some_field', true) each time you want to output a field creates an additional database query with every call and using ACFs functions get_field() & the_field() for example can generate more than one query per field which on pages with a large amount of post data can really add up. Using WP_Metaful will only generate 1 query for all pieces of post meta drastically reducing the numbers of queries you might have on a page.

## Examples

### Set up:
```php
$meta = new WP_Metaful;
```

### ACF Repeater
```php
<?php if ($repeater = $meta->repeater('repeater_field')): ?>
	
	<div>

		<?php foreach ($repeater as $key => $value): ?>

			<p><?php echo $meta->repeaterItem('repeater_item', $key); ?></p>

		<?php endforeach ?>

	</div>

<?php endif ?>
```
