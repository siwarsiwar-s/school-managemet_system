@extends('admin.admin_master')
@section('admin')

<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
      
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"> Assign Subject Details</h3>
                            <a href="{{ route('assign.subject.add') }}" style="float: right" class="btn btn-rounded btn-success mb-5">Add Assign Subject</a>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <h4><strong>Assign Subject:</strong> {{ $detailsData['0']['student_class']['name'] }}</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%">SL</th>
                                            <th>Subject</th>
                                            <th width="20%">Full Mark</th>
                                            <th width="20%">Pass Mark</th>
                                            <th width="20%">subjective Mark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allData as $key => $detail)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $detail['school_subject']['name'] }}</td>
                                                <td>{{ $detail->full_mark }}</td>
                                                <td>{{ $detail->pass_mark }}</td>
                                                <td>{{ $detail->subjective_mark }}</td>
                                                <td>
                                                    <a href="{{ route('fee.amount.edit', $detail->fee_category_id) }}" class="btn btn-info">Edit</a>
                                                    <a href="{{ route('fee.amount.details', $detail->fee_category_id) }}" class="btn btn-primary">Details</a>
                                                </td>
                                            </tr>
                                        @endforeach    
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
</div>

@endsection
