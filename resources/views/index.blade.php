        <div class="row">
            <div class="col-sm-6 align-self-end" style="text-align:left;">
                <h6>Total Data: <span id="total_records"></span></h6>
            </div>
            <div class="col-sm-6 pb-1 align-self-end"  style="text-align:right;">
                @hasanyrole('Super Admin|Admin')
                <!-- Button trigger modal -->
                <button id="addFilebtn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#addFileModal">
                    Add
                </button>
                @endhasanyrole
            </div>
        </div>
        <div class="table-responsive" style="font-size:14px">
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th width="50">
                            <a href="javascript:ajaxLoad('{{url('/?field=id&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc'))}}')">
                                ID{{request()->session()->get('field')=='id'?(request()->session()->get('sort')=='asc'?'▴':'▾'):''}}
                            </a>
                        </th>
                        <th width="80">
                            <a href="javascript:ajaxLoad('{{url('/?field=date&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc'))}}')">
                                Date{{request()->session()->get('field')=='date'?(request()->session()->get('sort')=='asc'?'▴':'▾'):''}}
                            </a>
                            <small class="d-block">yyyy/mm/dd</small>
                        </th>
                        <th width="80">
                            <a href="javascript:ajaxLoad('{{url('/?field=file_name&sort='.(request()->session()->get('sort')=='asc'?'desc':'asc'))}}')">
                                File Name{{request()->session()->get('field')=='file_name'?(request()->session()->get('sort')=='asc'?'▴':'▾'):''}}
                            </a>
                        </th>
                        <th width="200">
                            Content
                        </th>
                        <th width="40">
                            Division
                        </th>
                        <th width="1%"></th>
                        @hasanyrole('Super Admin|Admin')
                        <th width="1%"></th>
                        <th width="1%"></th>
                        @endhasanyrole
                    </tr>
                </thead>
                <tbody>
                    @if (count($archiveFiles) > 0)
                        @foreach ($archiveFiles as $row)
                            <tr>
                                <td class="align-middle">{{ $row->id }}</td>
                                <td class="align-middle">{{ $row->date }}</td>
                                <td class="align-middle">{{ $row->file }}</td>
                                <td style="text-align:left">{{ str_limit($row->content, 100) }}</td>
                                <!-- [$row->division_id - 1] because it was converted to an array and was reindexed -->
                                <td class="align-middle">{{ isset($division_name[$row->division_id - 1]['div_name']) ? $division_name[$row->division_id - 1]['div_name'] : 'No Division' }}</td>
                                <td class="align-middle"> <a style="font-size:12px" href="{{route('view', ['id' => $row->id])}}" target="_blank" class="btn btn-success">View</a> </td>
                                @hasanyrole('Super Admin|Admin')
                                <td class="align-middle"> <button style="font-size:12px" type="button" class="btn btn-info" data-toggle="modal" data-target="#editFileModal" onclick="ajaxEdit('{{ route('edit', ['id' => $row->id]) }}')">Edit</button> </td>
                                <td class="align-middle"> 
                                    <a style="font-size:12px" href="javascript:if(confirm('Are you sure want to delete?')) ajaxDelete('{{ route('destroy', ['id' => $row->id]) }}','{{csrf_token()}}')" class="btn btn-danger">X</a>
                                </td>
                                @endhasanyrole
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">
                                <p style="font-size:20px">No Records Found.</p>
                                <p style="font-size:14px"><span style="color:red;">Change the Division</span> or <span style="color:red;">Empty the Search Field</span></p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <ul class="pagination">
                {{ $archiveFiles->links() }}
            </ul>
        </div>

@include('modal.addModal')
@include('modal.editModal')

<script>
    $('.custom-file-input').on('change',function(){
        $(this).next('.form-control-file').addClass("selected").html($(this).val());
    });
</script>
