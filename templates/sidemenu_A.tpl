
<style>
	@media (min-width: 768px) {
	  .navbar-collapse {
		height: auto;
		border-top: 0;
		box-shadow: none;
		max-height: none;
		padding-left:0;
		padding-right:0;
	  
	  }
	  .navbar-collapse.collapse {
		display: block !important;
		width: auto !important;
		padding-bottom: 0;
		overflow: visible !important;
	  }
	  
	  .navbar-collapse.in {
		overflow-x: visible;
	  }

	.navbar
	{
		max-width:300px;
		margin-right: 0;
		margin-left: 0;
	}	

	.navbar-nav,
	.navbar-nav > li,
	.navbar-left,
	.navbar-right,
	.navbar-header
	{float:none !important;}

	.navbar-right .dropdown-menu {left:0;right:auto;}
	.navbar-collapse .navbar-nav.navbar-right:last-child {
		margin-right: 0;
	}

</style>

<nav class="navbar navbar-stacked" role="navigation">	
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button class="navbar-toggle" type="button" data-target=".navbar-ex1-collapse" data-toggle="collapse">
      <span class="sr-only">Toggle navigation</span>
      <div class="icon-bar five-up">---</div>
      <span class="icon-bar">---</span>
      <span class="icon-bar">---</span>
    </button>
    <a class="navbar-brand" href="#">HSC</a>
  </div>
  
    <!-- Collect the nav links, forms, and other content for toggling -->  
		<div class="collapse navbar-collapse navbar-ex1-collapse" id="main-menu" >
			<ul class="nav navbar-default ">
				<li><a href="index.php" title="home">Home</a></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Maintenance">Maintenance<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="content.php?template=templates/admin/managemembers.html&prefix=p_&action=adminedit&pid=-1">Manage Contacts </a></li>
							<li><a href="content.php?template=templates/admin/managecategories.html&prefix=p_">Manage Categories</a></li>
							<li><a href="content.php?template=templates/admin/managerentals.html&prefix=p_">Charters/Rentals</a></li>
							<li><a href="content.php?template=templates/admin/managelocations.html&prefix=p_">Manage Locations</a></li>
							<li><a href="content.php?template=templates/admin/managerates.html&prefix=p_">Manage Rates</a></li>
						</ul>
				</li>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Bookings">Bookings<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="content.php?template=templates/booking1.html">Take Bookings</a></li>
							<li><a href="content.php?template=templates/calendar.html&editoptions=function|booking|invoiced|delete">View Calendar</a></li>
						</ul>
				</li>
				<li role="separator" class="divider"></li>

				<li class="dropdown"><a href="administrate/administrate.php?action=exportmembers" class="dropdown-toggle" data-toggle="dropdown">Export<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="content.php?template=templates/exportinvoices.html" title="Export Invoices">Export Invoices</a></li>
							<li><a href="#" title="Export Contacts">Export Contacts</a></li>
						</ul>

				</li>

				<li role="separator" class="divider"><br/></li>
				<li><a href="content.php?action=backup">Backup</a></li>
				<li role="separator" class="divider"><br/></li>

				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Account<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="content.php?action=edit">Edit Profile</a></li>
						<li><a href="content.php?action=edit&template=templates/admin/editdescription.html">Edit Description</a></li>
					</ul>
				</li>
				<li><a href="http://help.hscboats.co.uk" title="Help" target="_blank()" >Help   </a></li>
				<li role="separator" class="divider"></li>
				<li><a href="content.php?action=signout" >Sign Out</a></li>
			</ul>
		</div><!-- navbar-collapse -->

</nav>



