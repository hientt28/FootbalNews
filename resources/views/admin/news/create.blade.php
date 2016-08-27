<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top :15%;">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title blue header">
              News Information
          </h4>
      </div>
      {{ Form::open(['route' => 'admin.news.store', 'method' => 'post']) }}
      <div class="modal-body">
          <div class="ui form">
                <div class="field">
                    <div class="two fields">
                        <div class="field">
                          <label >Author</label>
                          <label>
                                <i class="paint brush icon"></i> {{ auth()->user()->name }}
                          </label>
                        </div>
                        <div class="field">
                            <label>Match</label>
                            <div id="matches-dropdown">
                                <div id="matches-list"></div>
                            </div>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                          <label>Title</label>
                          <input type="text" name="title"/>
                        </div>
                        <div class="field">
                          <label>Image</label>
                          {{Form::file('image', ['class' => 'ui blue label'])}}
                        </div>
                    </div>
                </div>  
                <div class="field">
                    <label>Content</label>
                    <div id="content"></div>
                </div>
          </div> 
      </div>
      <div class="modal-footer">
          <button type="button" class="ui blue button add-news" data-dismiss="modal"><i class="plus icon"></i>Add</button>
          <button type="button" class="ui red button" data-dismiss="modal"><i class="cancel icon"></i>Close</button>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>