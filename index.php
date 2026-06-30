<?php
get_header();
?>
<main id="primary" class="site-main section">
    <div class="container flow">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('content-page'); ?>>
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <h1><?php esc_html_e('Content is being prepared', 'dgutheater'); ?></h1>
        <?php endif; ?>
    </div>
</main>
<?php
get_footer();

