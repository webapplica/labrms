{{ Form::open(array('class' => 'form-horizontal','method'=>'post','route'=>'type.store','id'=>'itemTypeForm')) }}
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('category','Category') }}
    {{ Form::select('category',$category,Input::old('category'),[
      'class'=>'form-control',
      'placeholder'=>'Description'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('name','Item Type Name') }}
    {{ Form::text('name',Input::old('name'),[
      'class'=>'form-control',
      'placeholder'=>'Item name'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('description','Description (Optional)') }}
    {{ Form::textarea('description',Input::old('description'),[
      'class'=>'form-control',
      'placeholder'=>'Description'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    <button class="btn btn-primary btn-block btn-lg" type="submit">
      <span class="glyphicon glyphicon-check"></span> <span>Submit</span>
    </button>
  </div>
</div>
{{ Form::close() }}
<script>
  $(document).ready(function(){

    @if( Session::has("success-message") )
        swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif

    @if( Session::has("error-message") )
        swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    $('#submit').click(function(){
      swal({
        title: "Are you sure?",
        text: "This will submit an item type with the following fields.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, submit it!",
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: false
      },function(confirm){
        if(confirm){
          $('#itemTypeForm').submit();
        }else{
          swal('Cancel','Operation Cancelled','error');
        }
      });
    });

    $('#page-body').show();
  });
</script>
