<div id="usermatchWindow" style="display:none;">
    <div id="usermatchWindowHeader">
        <h4 class="ui dividing header">Bet A Match</h4>
    </div>
    <div style="overflow: hidden;" id="usermatchWindowContent">
    	<div class="ui form">
	    	<div class="field">
			    <div class="two fields">
			      	<div class="field">
				       <label>User</label>
				       <label>{{ auth()->user()->name }}</label>
			      	</div>	
			      	<div class="field">
				      	<label>Match</label>
				      	<label id="match-select"></label>
			      	</div>
			    </div>
		  	</div>
		  	<div class="field">
			    <div class="two fields">
			      	<div class="field">
				       <label>Team Guess</label>
				       <label id="team-guess"></label>
			      	</div>	
			      	<div class="field">
				      	<label>Result</label>
				      	<label id="result"></label>
			      	</div>
			    </div>
		  	</div>
		  	<div class="field">
		  		<div class="two fields">
			      	<div class="field">
				       <label>Balance</label>
				       <div id="balance">5000$</div>
			      	</div>	
			      	<div class="field">
				       <label>Price</label>
				       <div id="price">
				       </div>
			      	</div>	
			    </div>  	
		  	</div>
		  	<div class="field">
		  	<button class="ui button blue" name="bet-match"><i class="dollar icon"></i> Bet</button>
		  	</div>
		</div> 	
    </div>
</div>  
