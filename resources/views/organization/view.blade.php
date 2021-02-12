@extends('layout.app')
@section('title', 'View Organization')

@section('content')

<div class="card col-8 offset-2">

    <div class="card-body">

        <div class="card-title h4">
            <span class="fas fa-fw fa-info-circle"></span> Organization Details
            <div class="float-end">
                <button type="button" class="btn btn-sm btn-info" id="btn-edit"> <span class="fas fa-fw fa-edit"></span> Edit</button>
                <button type="button" class="btn btn-sm btn-dark" id="btn-cancel-edit" hidden><span class="fas fa-fw fa-ban"></span> Cancel</button>
                <button type="button" class="btn btn-sm btn-warning" id="btn-delete"><span class="fas fa-fw fa-trash"></span> Delete</button>
                <a href="{{ route('organization') }}" class="btn btn-sm btn-secondary"> <span class="fas fa-fw fa-long-arrow-alt-left"></span> Back</a>
            </div>
        </div>

        <hr>

        <small class="text-danger required-field" hidden>Fields with * are required</small>

        <form method="post" class="mt-3" id="update-org-form">

            @csrf

            <div class="row g-2">

                <div class="col-6">

                    <div class="form-floating mb-3 name-wrapper">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Organization Name" value="{{ $org['name'] }}" readonly>
                        <label for="name">Organization Name *</label>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-success" id="btn-submit" hidden> <span class="fas fa-fw fa-save"></span> Submit</button>
        </form>

    </div>

</div>



<div class="card col-8 offset-2 mt-4">
    <div class="card-body">

        <div class="card-title h4">
            <span class="fas fa-fw fa-users"></span> Members
            <div class="float-end">
                <button type="button" class="btn btn-sm btn-primary" id="btn-add-member" data-bs-toggle="modal" data-bs-target="#modal-add-member"><span class="fas fa-fw fa-user-plus"></span> Add Member</button>
            </div>
        </div>

        <hr>

        <table class="table table-responsive table-hover table-striped tbl-member">
            <thead>
                <th>Name</th>
                <th>Organization</th>
                <th></th>
            </thead>
            <tbody>
                @if(count($members) == 0)
                <tr>
                    <td colspan="3" class="text-center">

                        <h3>
                            No records
                        </h3>
                    </td>

                </tr>
                @else
                @foreach($members as $item)

                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ count($item['organization']) }}</td>
                    <td><button class="btn btn-sm btn-danger float-end" id="member-delete" data-id="{{ $item['id'] }}"><span class="fas fa-fw fa-times"></span></button></td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>

    </div>

</div>



<!-- Modal -->
<div class="modal fade" id="modal-add-member" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <form id="add-member-form">

                    @csrf

                    <div class="col-10 offset-1">

                        <select class="form-select" id="member" name="member[]" multiple="multiple">

                            <option disabled>Select Member</option>
                            @foreach($mem_list as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach

                        </select>

                        <button type="submit" class="btn btn-primary mt-3" id="add-member-btn">Submit</button>

                    </div>
                </form>


            </div>

        </div>
    </div>
</div>


@endsection

@section('scripts')

<script>
    var xhr = null;

    function btn_edit_clicked() {

        $('#btn-edit').click(function() {

            $(this).attr('hidden', true);
            $('#btn-cancel-edit, #btn-submit, .required-field').removeAttr('hidden');
            $('#name').removeAttr('readonly');

        });

    }



    function btn_cancel_edit_clicked() {

        $('#btn-cancel-edit').click(function() {

            $(this).attr('hidden', true);
            $('#btn-submit, .required-field').attr('hidden', true);
            $('#btn-edit').removeAttr('hidden');
            $('#name').attr('readonly', true);

        });

    }



    function btn_add_member() {

        let myModalEl = document.getElementById('modal-add-member');

        $('#btn-add-member').click(function() {

            myModalEl.addEventListener('shown.bs.modal', function(event) {

                $('#member').select2({
                    placeholder: "Select Member",
                    allowClear: true,
                });

            });

        });

        myModalEl.addEventListener('hide.bs.modal', function(event) {
            $("#member").val('').trigger('change');
        })

    }



    function update() {

        $('#update-org-form').submit(function(e) {

            e.preventDefault();

            let formData = new FormData(this);
            let frm_btn = '#btn-submit';

            formData.append('org_id', "{{ $org['id'] }}");

            xhr = $.ajax({

                url: "{{ route('organization.update') }}",
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function() {

                    $(frm_btn).attr('disabled', 'disabled');
                    $(frm_btn).empty();
                    $(frm_btn).append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');

                    $('.name_err_msg').remove();
                    $('#name').removeClass('is-invalid');


                    if (xhr != null) {
                        xhr.abort();
                    }

                },

                success: function(data) {
                    $(frm_btn).removeAttr('disabled');
                    $(frm_btn).empty();
                    $(frm_btn).html('<span class="fas fa-fw fa-lg fa-save"></span> Submit');

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Your work has been saved',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    $("#btn-cancel-edit").trigger("click");
                },

                error: function(data) {

                    $(frm_btn).removeAttr('disabled');
                    $(frm_btn).empty();
                    $(frm_btn).html('<span class="fas fa-fw fa-lg fa-save"></span> Submit');

                    error = data.responseJSON;

                    if (data.status == 500) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                    } else {

                        $.each(error.errors, function(k, v) {

                            if (k == 'name') {
                                let name_err_msg = '<div class="invalid-feedback name_err_msg">' + v + '</div>';
                                $('#name').addClass('is-invalid');
                                $('.name-wrapper').append(name_err_msg);
                            } else {}
                        })

                    }

                }
            });

        });

    }



    function delete_org() {

        $('#btn-delete').click(function() {

            Swal.fire({

                title: 'Are you sure?',
                html: 'Delete <b>{{ $org["name"] }}</b>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                showCancelButton: true,
                allowOutsideClick: false,

            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Deleting',
                        type: 'info',
                        html: 'Please wait...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                    });


                    xhr = $.ajax({
                        url: "{{ route('organization.delete') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: "{{ $org['id'] }}",
                        },

                        beforeSend: function() {

                            if (xhr != null) {
                                xhr.abort();
                            }

                        },

                        success: function(data) {

                            Swal.fire({
                                title: 'Deleted!',
                                html: 'Organization has been deleted.',
                                icon: 'success',
                                allowOutsideClick: false,

                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.replace("{{ route('organization') }}");
                                }

                            });

                        }

                    });

                }
            });

        });

    }



    function delete_member() {

        $(document).off('click', '#member-delete').on('click', '#member-delete', function(e) {

            var trigger_btn = e.target,
                parent_tr = $(trigger_btn).closest('tr').get(0);

            let member_id = $(this).data('id'),
                org_id = "{{ $org['id'] }}",
                member_name = $(parent_tr).find("td:first").text();

            Swal.fire({

                title: 'Are you sure?',
                html: 'Delete <b>' + member_name + '</b>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                showCancelButton: true,
                allowOutsideClick: false,

            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Deleting',
                        type: 'info',
                        html: 'Please wait...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }

                    })

                    xhr = $.ajax({
                        url: "{{ route('organization.delete-member') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            member_id: member_id,
                            org_id: org_id,
                        },

                        beforeSend: function() {

                            if (xhr != null) {
                                xhr.abort();
                            }

                        },

                        success: function(data) {

                            parent_tr.remove();

                            Swal.fire({
                                title: 'Deleted!',
                                html: 'Member has been deleted.',
                                icon: 'success',
                                allowOutsideClick: false,

                            }).then((result) => {

                                if (result.isConfirmed) {
                                    
                                    location.reload();
                                
                                }

                            });


                        }
                    });

                }

            });

        });

    }



    function add_member() {

        $('#add-member-form').submit(function(e) {

            e.preventDefault();

            let formData = new FormData(this);
            let frm_btn = '#add-member-btn';

            formData.append('org_id', "{{ $org['id'] }}");

            xhr = $.ajax({

                url: "{{ route('organization.add-member') }}",
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function() {

                    $(frm_btn).attr('disabled', 'disabled');
                    $(frm_btn).empty();
                    $(frm_btn).append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');

                    if (xhr != null) {
                        xhr.abort();
                    }

                },

                success: function(data) {
                    $(frm_btn).removeAttr('disabled');
                    $(frm_btn).empty();
                    $(frm_btn).html('<span class="fas fa-fw fa-lg fa-save"></span> Submit');

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Your work has been saved',
                        showConfirmButton: false,
                        timer: 1000
                    });

                    $("#member").val('').trigger('change');

                    setTimeout(() => {

                        $('#modal-add-member').modal('hide');
                        location.reload();

                    }, 1500);
                },

                error: function(data) {

                    $(frm_btn).removeAttr('disabled');
                    $(frm_btn).empty();
                    $(frm_btn).html('<span class="fas fa-fw fa-lg fa-save"></span> Submit');

                    error = data.responseJSON;

                    if (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b> ' + error.errors + '</b>',
                        })
                    }

                }
            });

        });

    }



    $(function() {

        btn_edit_clicked();

        btn_cancel_edit_clicked();

        update();

        delete_org();

        btn_add_member();

        add_member();

        delete_member();


    })
</script>

@endsection