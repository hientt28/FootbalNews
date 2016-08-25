@extends('layouts.app')

@section('content')
@include('admin.news.create')
<div class="page-content">
	<div class="ui form">
		<div class="ui floated field">
			<div class="two fields">
				<div class="field">
					<h2 class="ui header blue">News List</h2>
				</div>
				<div class="field">
    				<button class="ui animated blue button" tabindex="0" data-toggle="modal" data-target="#myModal">
					  	<div class="hidden content"><i class="plus icon"></i></div>
					  	<div class="visible content">
					    	<i class="plus icon"></i>
					  	</div>
					</button>
					<div class="ui vertical teal animated button" tabindex="0">
					  	<div class="hidden content">
					  		<i class="edit icon"></i>
					  		Update News
				  		</div>
					  	<div class="visible content">
					    	<i class="edit icon"></i>
					  	</div>
					</div>
					<div class="ui vertical red animated button" tabindex="0">
					  	<div class="hidden teal content">
					  		<i class="trash icon"></i>
					  		Delete News
				  		</div>
					  	<div class="visible content">
					    	<i class="trash icon"></i>
					  	</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		<div class="ui items">
		@if(count($news) > 0)
			@foreach($news as $n)
				  <div class="item ticky">
			    	<div class="image lazy" primary="{{$n->id}}" data-original="{{ $n->image ? $n->image : asset('images/football-wp.jpg') }}" onclick="news.showDetail(&#39;{{ route('admin.news.show', ['news' => $n->id]) }}&#39;)">
				     	<img src="{{ $n->image ? $n->image : asset('images/football-wp.jpg') }}">
				    </div>
				    <div class="content">
				      <a class="header">{{ $n->title }}</a>
				      <div class="meta">
				        <span>{!! $n->content !!}</span>
				      </div>
				      <div class="description">
				       	<div class="ui star rating" data-rating="3"></div>
				      </div>
				      <div class="extra">
					      	<div class="fb-like" data-href="http://football.com/admin/news/{{$n->id}}" data-layout="standard" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
					      	<div class="fb-follow" data-href="http://football.com/admin/news/{{$n->id}}" data-layout="standard" data-size="small" data-show-faces="true"></div>
					      	<i onclick="newsBuilder.renderComments('{{$n->id}}')" class="comment icon"></i>
					      	<i onclick="newsBuilder.hideComments('{{$n->id}}')" class="arrow up icon"></i>
				      </div>
				    </div>
				  </div>
				  <div class="comments-{{$n->id}}" style="display:none;">
				  		<div class="ui comments">
						  <div class="comments-area">
						  		<h4 class="ui header blue">Comments</h4>
						  		<div class="comment-list">
						  			@if(count($n->comments) > 0)
						  			@foreach($n->comments as $comment)
						  				<div class="comment">
										    <a class="avatar">
										      <img src="{{ asset('images/man.png') }}">
										    </a>
									    	<div class="content">
										      <a class="author"></a>
										      <div class="metadata">
										        <span class="date">Today at 5:42PM</span>
										      </div>
										      <div class="text">
										        {{ $comment->content }}
										      </div>
										      <div class="actions">
										        <a class="reply">Reply</a>
										      </div>
										    </div>
									  	</div>
									@endforeach  	
						  			@endif
						  		</div>
						  </div>
				  			<form class="ui reply form">
							    <div class="field">
							      	<textarea name="comment-text-{{$n->id}}"></textarea>
							    </div>
							    <div class="ui blue labeled submit icon button addRep" onclick="newsBuilder.addComment(&#39;{{ $n->id}}&#39;)">
							      	<i class="icon edit"></i> Add Reply
							    </div>
							 </form>
				  		</div>
			      </div>	
			@endforeach
		@else
		<div class="ui segment">
			no news 
		</div>	
		@endif	
		</div>
		<div class="ui modal segment">
			<div class="ui comments">
				 <h3 class="ui dividing header">Comments</h3>
				  <div class="comment">
					    <a class="avatar">
					      <img src="{{ asset('images/man.png') }}">
					    </a>
				    	<div class="content">
				      <a class="author">Matt</a>
				      <div class="metadata">
				        <span class="date">Today at 5:42PM</span>
				      </div>
				      <div class="text">
				        How artistic!
				      </div>
				      <div class="actions">
				        <a class="reply">Reply</a>
				      </div>
				    </div>
				  </div>
			  <div class="comment">
			    <a class="avatar">
			      <img src="{{ asset('images/man.png') }}">
			    </a>
			    <div class="content">
			      <a class="author">Elliot Fu</a>
			      <div class="metadata">
			        <span class="date">Yesterday at 12:30AM</span>
			      </div>
			      <div class="text">
			        <p>This has been very useful for my research. Thanks as well!</p>
			      </div>
			      <div class="actions">
			        <a class="reply">Reply</a>
			      </div>
			    </div>
			    <div class="comments">
			      <div class="comment">
			        <a class="avatar">
			         	<img src="{{ asset('images/young.png') }}">
			        </a>
			        <div class="content">
			          <a class="author">Jenny Hess</a>
			          <div class="metadata">
			            <span class="date">Just now</span>
			          </div>
			          <div class="text">
			            Elliot you are always so right :)
			          </div>
			          <div class="actions">
			            <a class="reply">Reply</a>
			          </div>
			        </div>
			      </div>
			    </div>
			  </div>
			  <div class="comment">
			    <a class="avatar">
			      <img src="{{ asset('images/young.png') }}">
			    </a>
			    <div class="content">
			      <a class="author">Joe Henderson</a>
			      <div class="metadata">
			        <span class="date">5 days ago</span>
			      </div>
			      <div class="text">
			        Dude, this is awesome. Thanks so much
			      </div>
			      <div class="actions">
			        <a class="reply">Reply</a>
			      </div>
			    </div>
			  </div>
			  <form class="ui reply form">
			    <div class="field">
			      <textarea></textarea>
			    </div>
			    <div class="ui blue labeled submit icon button">
			      <i class="icon edit"></i> Add Reply
			    </div>
			  </form>
			</div>	
		</div>
 </div>   
	
@endsection