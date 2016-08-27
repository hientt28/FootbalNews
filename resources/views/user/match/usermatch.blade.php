<div id="usermatchWindow" style="display:none;">
    <div id="usermatchWindowHeader">
        <h4 class="ui white header">Bet A Match</h4>
    </div>
    <div style="overflow: hidden;" id="usermatchWindowContent">
    <div id="result-bet-container">
    	<div id="result-bet"></div>
    </div>
    	<div class="ui form">
    	<form name="form-bet">
	    	<div class="field">
			    <div class="two fields">
			      	<div class="field">
				       <label class="ui  blue header ">User</label>
				       <label>{{ auth()->user()->name }}</label>
			      	</div>	
			      	<div class="field">
				      	<label class="ui  blue header ">Match</label>
				      	<label id="match_bet"></label>
			      	</div>
			    </div>
		  	</div>
		  	<div class="field">
			    <div class="two fields">
			      	<div class="field">
				       <label class="ui  blue header ">Team Guess</label>
				       <label id="team-guess"></label>
			      	</div>	
			      	<div class="field">
				      	<label class="ui  blue header ">Result</label>
				      	<label id="result"></label>
			      	</div>
			    </div>
		  	</div>
		  	<div class="field">
		  		<div class="two fields">
			      	<div class="field">
				       <label class="ui  blue header ">Balance</label>
				       <div id="balance">5000$</div>
			      	</div>	
			      	<div class="field">
				       <label class="ui  blue header ">Price</label>
				       <div id="price">
				       </div>
			      	</div>	
			    </div>  	
		  	</div>
		  	<div class="field">
		  		<div class="two fields">
			      	<div class="field">
			      		<button class="ui button blue" name="bet-match"><i class="dollar icon"></i> Bet</button>
			      	</div>
			      	<div class="field ui red header">
			      		<div class="ui segment">
		  					Bonus : <label class="bonus">0 d</label>
		  				</div>
	  				</div>
			    </div>
			</div>      	
		  	</form>
		</div> 	
    </div>
</div>  
