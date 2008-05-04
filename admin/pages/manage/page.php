					<h1><?php echo __("Manage Pages"); ?></h1>
<?php if (isset($_GET['updated'])): ?>
					<div class="success"><?php echo __("Page updated."); ?> <a href="<?php echo $route->url(Page::info("url", $_GET['updated'])."/"); ?>"><?php echo __("View &raquo;"); ?></a></div>
<?php elseif (isset($_GET['deleted'])): ?>
					<div class="success"><?php echo __("Page deleted."); ?></div>
<?php elseif (isset($_GET['reordered'])): ?>
					<div class="success"><?php echo __("Pages reordered."); ?></div>
<?php endif; ?>
					<h2><?php echo __("Need more detail?"); ?></h2>
					<form class="detail" action="index.php" method="get" accept-charset="utf-8">
						<input type="hidden" name="action" value="manage" />
						<input type="hidden" name="sub" value="page" />
						<div class="pad">
							<h3><?php echo __("Search&hellip;"); ?></h3>
							<input class="text" type="text" name="query" value="<?php if (!empty($_GET['query'])) echo fix($_GET['query']); ?>" id="query" /> <button type="submit" class="inline"><?php echo __("Search &rarr;"); ?></button>
						</div>
					</form>
					<br class="clear" />
					<br />
					<h2><?php echo __("All Pages"); ?></h2>
					<table border="0" cellspacing="0" cellpadding="0" class="wide manage">
						<tr class="head">
							<th width="30%"><?php echo __("Title"); ?></th>
							<th width="15%"><?php echo __("Created"); ?></th>
							<th width="15%"><?php echo __("Updated"); ?></th>
							<th width="15%"><?php echo __("Owner"); ?></th>
<?php if ($visitor->group()->can("edit_page") and $visitor->group()->can("delete_page")): ?>
							<th colspan="2" width="10%"></th>
<?php else: ?>
							<th width="10%"></th>
<?php endif; ?>
						</tr>
<?php
	if (!empty($_GET['query']))
		$pages = Page::find(array("where" => "`title` like :query or `body` like :query", "params" => array(":query" => "%".$_GET['query']."%")));
	else
		$pages = Page::find();

	foreach ($pages as $page):
?>
						<tr>
							<td class="main"><a href="<?php echo $page->url(); ?>"><?php echo $page->title; ?></a></td>
							<td><?php echo when("%x", $page->created_at, true); ?></td>
							<td><?php if ($page->updated_at != "0000-00-00 00:00:00") echo when("%x", $page->updated_at, true); ?></td>
							<td class="center"><?php echo fallback($page->user()->full_name, $page->user()->login, true); ?></td>
<?php if ($visitor->group()->can("edit_page")): ?>
							<td class="center"><?php echo $page->edit_link('<img src="icons/edit.png" alt="edit" />'); ?></td>
<?php endif; ?>
<?php if ($visitor->group()->can("delete_page")): ?>
							<td class="center"><?php echo $page->delete_link('<img src="icons/delete.png" alt="delete" />'); ?></td>
<?php endif; ?>
						</tr>
<?php
	endforeach;
?>
					</table>
					<br />
					<h2>Organize Pages</h2>
					<form action="<?php url("reorder_pages"); ?>" method="post" accept-charset="utf-8">
<?php $theme->list_pages(false, null, "sort_pages", "page-item", true); ?>
						<br class="js_disabled" />
						<button type="submit" id="save" class="js_disabled positive" accesskey="s">
							<?php echo __("Reorder &rarr;"); ?>
						</button>
						<br class="js_disabled clear" />
					</form>
<?php if ($paginate->next_page()): ?>
					<a class="right button" href="<?php echo $paginate->next_page_url("page", false); ?>"><?php echo __("Next &raquo;"); ?></a>
<?php endif; ?>
<?php if ($paginate->prev_page()): ?>
					<a class="button" href="<?php echo $paginate->prev_page_url("page", false); ?>"><?php echo __("&laquo; Previous"); ?></a>
<?php endif; ?>
<?php if ($paginate->prev_page() or $paginate->next_page()): ?>
					<br class="clear" />
<?php endif; ?>