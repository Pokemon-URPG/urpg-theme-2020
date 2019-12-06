<head>
  <meta charset="utf-8">
  <meta name="theme-color" content="#ffb300"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
      <?php echo get_bloginfo('name');
        if (!is_front_page()):
            echo wp_title();
        endif;
      ?>
    </title>
    <meta name="description" content="<?php echo get_bloginfo('description'); ?>">
    <meta name="author" content="Vanessa Sickles">

  <?php wp_head(); ?>
</head>

<body>
    <?php get_template_part( 'navigation' ) ?>