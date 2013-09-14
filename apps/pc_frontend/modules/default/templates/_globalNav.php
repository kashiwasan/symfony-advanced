<?php if ($navs): ?>
<ul>
<?php foreach ($navs as $nav): ?>
<?php if (sa_is_accessible_url($nav->uri)): ?>
<li id="globalNav_<?php echo sa_url_to_id($nav->uri, true) ?>"><?php echo link_to($nav->caption, $nav->uri) ?></li>
<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>
