@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Edit Fee Amount</h4>
                </div>
                
                <div class="box-body">
                    <div class="row">
                        <div class="col">
                            <form method="post" action="{{ route('update.fee.amount',$editData[0]->fee_category_id) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="add_item">
                                            <!-- Fee Category Selection -->
                                            <div class="form-group">
                                                <h5>Fee Category<span class="text-danger">*</span></h5>
                                                <div class="controls">
                                                    <select name="fee_category_id" required class="form-control">
                                                        <option value="">Select Fee Category</option>
                                                        @foreach ( $fee_categories as $category )
                                                        <option value="{{$category->id}}" {{($editData[0]->fee_category_id == $category->id) ? "selected" : ""}}>{{$category->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <!-- Loop through existing data for each class and amount -->
                                            @foreach ( $editData as $edit )
                                            <div class="delete_whole_extra_item_add" id="delete_whole_extra_item_add">
                                                <div class="row">
                                                    <!-- Class Selection -->
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <h5>Student Class<span class="text-danger">*</span></h5>
                                                            <div class="controls">
                                                                <select name="class_id[]" required class="form-control">
                                                                    <option value="">Select Student Class</option>
                                                                    @foreach ( $classes as $class )
                                                                    <option value="{{$class->id}}" {{($edit->class_id == $class->id) ? "selected" : ""}}>{{$class->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Amount Input -->
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <h5>Amount<span class="text-danger">*</span></h5>
                                                            <div class="controls">
                                                                <input type="text" name="amount[]" value="{{$edit->amount}}" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Add/Remove Buttons -->
                                                    <div class="col-md-2" style="padding-top:25px;">
                                                        <span class="btn btn-success addeventmore">
                                                            <i class="fa fa-plus-circle"></i> 
                                                        </span>
                                                        <span class="btn btn-danger removeeventmore">
                                                            <i class="fa fa-minus-circle"></i> 
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="text-xs-right">
                                        <input type="submit" class="btn btn-rounded btn-info mb-5" value="Update">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    var counter = 0;

    // Add new row of input fields dynamically
    $(document).on("click", ".addeventmore", function(){
        var whole_extra_item_add = $('#whole_extra_item_add').html();
        $(this).closest(".add_item").append(whole_extra_item_add);
        counter++;
    });

    // Remove a row of input fields
    $(document).on("click", '.removeeventmore', function(){
        $(this).closest(".delete_whole_extra_item_add").remove();
        counter--;
    });
});
</script>

<!-- Template for new rows -->
<div style="display: none;">
    <div id="whole_extra_item_add">
        <div class="delete_whole_extra_item_add" id="delete_whole_extra_item_add">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <h5>Student Class<span class="text-danger">*</span></h5>
                        <div class="controls">
                            <select name="class_id[]" required class="form-control">
                                <option value="">Select Student Class</option>
                                @foreach ( $classes as $class )
                                <option value="{{$class->id}}">{{$class->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="form-group">
                        <h5>Amount<span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="amount[]" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2" style="padding-top:25px;">
                    <span class="btn btn-success addeventmore">
                        <i class="fa fa-plus-circle"></i> 
                    </span>
                    <span class="btn btn-danger removeeventmore">
                        <i class="fa fa-minus-circle"></i> 
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
