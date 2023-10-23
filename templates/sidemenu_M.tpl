<nav class="navbar navbar-stacked" role="navigation">
	<div class="container-fluid" >
	  <!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="#">HSC</a>
		</div>

    <!-- Collect the nav links, forms, and other content for toggling -->  
		<div class="collapse navbar-collapse" id="main-menu" >
			<ul class="nav navbar-nav">
				<li><a href="index.php" title="home">Home</a></li>
				<li><a href="content.php?action=signout" >Sign Out</a></li>
				<li><a href="content.php?action=edit">Edit Profile</a></li>
				<li><a href="content.php?action=edit&template=templates/admin/editdescription.html">Edit Description</a></li>
				<li><a href="content.php?template=templates/admin/editoffers.html">Maintain Offers</a></li>
				<li><a href="content.php?template=templates/admin/editevents.html">Maintain Events</a></li>

				<li><a href="content.php?action=payments">Manage Subscriptions</a></li>

				<li><hr/></li>
				<li><a href="content.php?template=templates/directory.html&categoryid=0" >Member Directory</a></li>
				<li><a href="content.php?template=templates/offerlist.html">View Offers</a></li>
				<li><a href="content.php?template=templates/eventlist.html">View Events</a></li>

			</ul>
		</div><!-- navbar-collapse -->
	</div> <!-- container fluid -->
</nav> 

