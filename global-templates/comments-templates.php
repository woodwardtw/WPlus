<?php // Templates for ajax comments ?>

<?php /* Wrap for comments in general should none be present */ ?>
<script type="text/html" id="tmpl-comment-wrap">
	<div class="comments">
		<h2 class="comments-title">Comments</h2>
		<ol class="commentlist"></ol>
	</div>
</script>

<?php /* Markup for a single comment when inserted into the DOM */ ?>
<script type="text/html" id="tmpl-comment-single">
	<li class="{{data.comment_class}}" id="li-comment-{{data.comment_ID}}">
		<div id="comment-{{data.comment_ID}}" class="comment">
			<div class="comment-meta comment-author vcard">
				{{{data.gravatar}}}
				<div class="comment-meta-content">							
					<cite class="fn">
						<# if ( data.comment_author_url ) { #>
							<a href="{{data.comment_author_url}}" rel="nofollow" class="url">
						<# } #>
						{{data.comment_author}}
						<# if ( data.comment_author_url ) { #>
							</a>
						<# } #>
					</cite>
					<p>
						<a href="<?php the_permalink(); ?>#comment-{{data.comment_ID}}">
							{{data.date_formatted}} at {{data.time_formatted}}
						</a>
					</p>
				</div> <!-- /comment-meta-content -->
			</div> <!-- /comment-meta -->
			<div class="comment-content post-content">
				<# if ( "1" !== data.comment_approved ) { #>
					<p class="comment-awaiting-moderation"><?php _e( 'Awaiting moderation', 'wilson' ); ?></p>
				<# } #>

				{{{data.content_formatted}}}
			</div><!-- /comment-content -->
		</div><!-- /comment-## -->
	</li>
	<!-- #comment-## -->
</script>