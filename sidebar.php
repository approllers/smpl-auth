		<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav side-nav">
					<li<?= ($page == 'dashboard.php') ? (' class="active"') : (''); ?>>
						<a href="dashboard.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
					</li><?php /*
					<li<?= ($page == 'links.php') ? (' class="active"') : (''); ?>>
						<a href="links.php"><i class="fa fa-fw fa-link"></i> Links</a>
					</li>
					<li<?= ($page == 'batches.php') ? (' class="active"') : (''); ?>>
						<a href="batches.php"><i class="fa fa-fw fa-database"></i> Batches</a>
					</li>
					<li<?= ($page == 'tags.php') ? (' class="active"') : (''); ?>>
						<a href="tags.php"><i class="fa fa-fw fa-tag"></i> Tags</a>
					</li>
					<li<?= ($page == 'sources.php') ? (' class="active"') : (''); ?>>
						<a href="sources.php"><i class="fa fa-fw fa-rss"></i> Sources</a>
					</li>*/ ?>
					<li<?= ($page == 'users.php' || $page == 'user.php' || $page == 'createuser.php') ? (' class="active"') : (''); ?>>
						<a href="users.php"><i class="fa fa-fw fa-users"></i> Users</a>
					</li><?PHP /*
					<li<?= ($page == 'statistics.php') ? (' class="active"') : (''); ?>>
						<a href="statistics.php"><i class="fa fa-fw fa-bar-chart-o"></i> Statistics</a>
					</li>*/ ?>
					<li<?= ($page == 'settings.php') ? (' class="active"') : (''); ?>>
						<a href="settings.php"><i class="fa fa-fw fa-cog"></i> Settings</a>
					</li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</nav>