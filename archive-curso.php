<?php get_header(); ?>
<section id="primary">
<div id="content" role="main" style="width: 100%">
<?php if ( have_posts() ) : ?>
<header class="page-header">
<h1 class="page-title">Cursos</h1>
</header>
<table>
<!-- Display table headers -->
<tr>
<th style="width: 200px"><strong>Título</strong></th>
<th><strong>Tipo de Curso</strong></th>
<th><strong>Alvo do Curso</strong></th>
<th><strong>Duração do Curso</strong></th>
<th><strong>Descrição do Curso</strong></th>
<th><strong>Legislação do Curso</strong></th>
</tr>
<!-- Start the Loop -->
<?php while ( have_posts() ) : the_post(); ?>
<!-- Display review title and author -->
<tr>
<td><a href="<?php the_permalink(); ?>">
<?php the_title(); ?></a></td>
<td><?php echo esc_html( get_post_meta( get_the_ID(), 'curso_type', true ) ); ?></td>
<td><?php echo esc_html( get_post_meta( get_the_ID(), 'curso_target', true ) ); ?></td>
<td><?php echo esc_html( get_post_meta( get_the_ID(), 'curso_duration', true ) ); ?></td>
<td><?php echo esc_html( get_post_meta( get_the_ID(), 'curso_description', true ) ); ?></td>
<td><?php echo esc_html( get_post_meta( get_the_ID(), 'curso_legislation', true ) ); ?></td>
</tr>
<?php endwhile; ?>
<!-- Display page navigation -->
</table>
<?php global $wp_query;
if ( isset( $wp_query->max_num_pages ) && $wp_query->max_num_pages > 1 ) { ?>
<nav id="<?php echo $nav_id; ?>">
<div class="nav-previous"><?php next_posts_link( '<span class="meta-nav">&larr;</span> Older reviews'); ?></div>
<div class="nav-next"><?php previous_posts_link( 'Newer reviews <span class= "meta-nav">&rarr;</span>' ); ?></div>
</nav>
<?php };
endif; ?>
</div>
</section>
<br /><br />
<?php get_footer(); ?>