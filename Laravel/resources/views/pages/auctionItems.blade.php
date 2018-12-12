	<!---  Active auctions grid --->
	<div class="album py-2" id="auctionsAlbum">
	    <div class="row">
	      <!--- Items list -->
	      @each('partials.proposalItem', $proposals, 'auction')
	    </div>
	</div>
