{{ Form::open(array('class' => 'form-horizontal','method'=>'post','route'=>'type.store','id'=>'itemTypeForm')) }}
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('category','Category') }}
    {{ Form::select('category',$category, isset($itemtype->category) ? $itemtype->category : old('category') ,[
      'class'=>'form-control'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('name','Item Type Name') }}
    {{ Form::text('name',  isset($itemtype->name) ? $itemtype->name : old('name'), [
      'class'=>'form-control',
      'placeholder'=>'Item name'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('description','Description (Optional)') }}
    {{ Form::textarea('description',  isset($itemtype->description) ? $itemtype->description : old('description'), [
      'class'=>'form-control',
      'placeholder'=>'Description'
    ]) }}
  </div>
</div>
{{ Form::close() }}
<script>
  $(document).ready(function(){

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

  });
</script>
