<div class="container-fluid pad_0">
	<div class="navbar-header" id="archive-browser">
		<div class="dropdown-box"><span class="list-heading">Filtrera efter Yrkesroll</span>
		  <?php
		  $categories = get_categories('taxonomy=filter_by_fun&hide_empty=0'); 
		  ?>
		  <select name="cat" id="fun" class="postform" >
		  	<option class="" value="0">Välj Yrkesroll</option>
			    <?php
		    	foreach($categories as $category)
			  	{ 
			  	?>
			        <option class="" value= <?php echo $category->term_id; ?> > 
			        	<?php echo $category->name; ?>
			        </option>
			        <?php
				}
			  ?>
		  </select>
		</div>
		<div class="dropdown-box"><span class="list-heading">Upptäck våra Produkter</span>
			  <?php
			  $categories = get_categories('taxonomy=filter_by_pro&hide_empty=0'); 
			  ?>
			  <select name="cat" id="pro" class="postform">
			  <option value="">Välj Produkt</option>
				  <?php
				  foreach($categories as $category)
					  { 
					  ?>
					        <option value= <?php echo $category->term_id; ?> > 
					        	<?php echo $category->name; ?>
					        </option>
					        <?php
					  }
				  ?>
			  </select>
		</div>
	</div>	 
</div>