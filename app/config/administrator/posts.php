<?php
return array(
/**
 * Model title
 *
 * @type string
 */
'title' => 'Posts',
/**
 * The columns array
 *
 * @type array
 */
'form_width' => 400,
'columns' => array(
    'title' => array(
        'title' => 'Title'
    ),
    'order' => array(
        'title' => 'Order'
    ),
    'image' => array(
        'title' => 'Image',
        'output' => '<img src="/uploads/images/thumbs/full/(:value)" height="100" />',
    ),
    'slug' => array(
        'title' => 'Link',
        'output' => '<a href="(:value)" target="_blank">(:value)</a>',
    ),
),
'model' => 'Post',
'single' => 'post',
'edit_fields' => array(
    'title' => array(
        'title' => 'Title',
        'type' => 'text'
    ),
    'order' => array(
        'title' => 'Order',
        'type' => 'number'
    ),
    'language' => array(
	    'type' => 'relationship',
	    'title' => 'Language',
	    'name_field' => 'title',
    ),
    'type' => array(
	    'type' => 'relationship',
	    'title' => 'Type',
	    'name_field' => 'title',
    ),
    'category' => array(
	    'type' => 'relationship',
	    'title' => 'Category',
	    'name_field' => 'title',
	    'constraints' => array('type' => 'categories') //this is the important bit!

    ),
    'albums' => array(
	    'type' => 'relationship',
	    'title' => 'Albums',
	    'name_field' => 'title', //using the getFullNameAttribute accessor
	),
    'image' => array(
        'title' => 'Image',
        'type' => 'image',
        'location' => public_path() . '/uploads/images/',
	    'naming' => 'random',
	    'length' => 20,
	    'size_limit' => 2,
	    'sizes' => array(
	        array(65, 57, 'crop', public_path() . '/uploads/images/thumbs/small/', 100),
	        array(220, 138, 'landscape', public_path() . '/uploads/images/thumbs/medium/', 100),
	        array(383, 276, 'fit', public_path() . '/uploads/images/thumbs/full/', 100)
	    )
    ),
    'content' => array(
	    'type' => 'wysiwyg',
	    'title' => 'Content',
	),

),
'filters' => array(
    'id',
    'title' => array(
        'title' => 'Title',
    ),
    'date' => array(
        'title' => 'Date',
        'type' => 'date',
    ),
),
'rules' => array(
    'title' => 'required',
    'order' => 'required|integer|min:18',
),
'before_save' => function(&$data)
{
    $data['slug'] = $data['title'];
},
);