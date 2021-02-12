@extends('layout.app')
@section('title', 'View Person')

@section('content')

<div class="card col-8 offset-2">

    <div class="card-body">
        <div class="card-title h4">
            <span class="fas fa-fw fa-user-circle"></span> Person Details
            <div class="float-end">
                <button type="button" class="btn btn-sm btn-info" id="btn-edit"> <span class="fas fa-fw fa-edit"></span> Edit</button>
                <button type="button" class="btn btn-sm btn-dark" id="btn-cancel-edit" hidden><span class="fas fa-fw fa-ban"></span> Cancel</button>
                <button type="button" class="btn btn-sm btn-warning" id="btn-delete"><span class="fas fa-fw fa-trash"></span> Delete</button>
                <a href="{{ route('person') }}" class="btn btn-sm btn-secondary"> <span class="fas fa-fw fa-long-arrow-alt-left"></span> Back</a>
            </div>
        </div>

        <hr>

        <small class="text-danger required-field" hidden>Fields with * are required</small>

        <form method="post" class="mt-3" id="update-person-form">

            @csrf

            <div class="row g-2">

                <div class="col-md">

                    <div class="form-floating mb-3 name-wrapper">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" value="{{ $person['name'] }}" readonly>
                        <label for="name">Full Name *</label>
                    </div>
                </div>

                <div class="col-md">

                    <div class="form-floating email-wrapper">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ $person['email'] }}" readonly>
                        <label for="email">Email *</label>
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
            <span class="fas fa-fw fa-dungeon"></span> Organizations
            <div class="float-end">
                <button type="button" class="btn btn-sm btn-primary" id="btn-add-org" data-bs-toggle="modal" data-bs-target="#modal-add-org"><span class="fas fa-fw fa-plus"></span> Add Organization</button>
            </div>
        </div>

        <hr>

        <table class="table table-responsive table-hover table-striped tbl-org">
            <thead>
                <th>Name</th>
                <th>Members</th>
                <th></th>
            </thead>
            <tbody>
                @if(count($person_org) == 0)
                <tr>
                    <td colspan="3" class="text-center">

                        <h3>
                            No records
                        </h3>
                    </td>

                </tr>
                @else
                @foreach($person_org as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ count($item['person']) }}</td>
                    <td><button class="btn btn-sm btn-danger float-end" id="org-delete" data-id="{{ $item['id'] }}"><span class="fas fa-fw fa-times"></span></button></td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>

    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="modal-add-org" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Organization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <form id="add-organization-form">

                    @csrf

                    <div class="col-10 offset-1">

                        <select class="form-select" id="organization" name="organization[]" multiple="multiple">

                            <option disabled>Select Organization</option>
                            @foreach($organizations as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach

                        </select>

                        <button type="submit" class="btn btn-primary mt-3" id="add-org-btn">Submit</button>

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
            $('#name, #email').removeAttr('readonly');

        });

    }



    function btn_cancel_edit_clicked() {

        $('#btn-cancel-edit').click(function() {

            $(this).attr('hidden', true);
            $('#btn-submit, .required-field').attr('hidden', true);
            $('#btn-edit').removeAttr('hidden');
            $('#name, #email').attr('readonly', true);

        });

    }



    function btn_add_org() {

        let myModalEl = document.getElementById('modal-add-org');

        $('#btn-add-org').click(function() {

            myModalEl.addEventListener('shown.bs.modal', function(event) {

                $('#organization').select2({
                    placeholder: "Select Organization",
                    allowClear: true,
                });

            });

        });

        myModalEl.addEventListener('hide.bs.modal', function(event) {
            $("#organization").val('').trigger('change');
        });

    }



    function update() {

        $('#update-person-form').submit(function(e) {

            e.preventDefault();

            let formData = new FormData(this);
            let frm_btn = '#btn-submit';

            formData.append('person_id', "{{ $person['id'] }}");

            xhr = $.ajax({

                url: "{{ route('person.update') }}",
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function() {

                    $(frm_btn).attr('disabled', 'disabled');
                    $(frm_btn).empty();
                    $(frm_btn).append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');

                    $('.name_err_msg, .email_err_msg').remove();
                    $('#name, #email').removeClass('is-invalid');


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
                        });

                    } else {

                        $.each(error.errors, function(k, v) {

                            if (k == 'name') {

                                let name_err_msg = '<div class="invalid-feedback name_err_msg">' + v + '</div>';
                                $('#name').addClass('is-invalid');
                                $('.name-wrapper').append(name_err_msg);

                            } else if (k == 'email') {

                                let email_err_msg = '<div class="invalid-feedback email_err_msg">' + v + '</div>';
                                $('#email').addClass('is-invalid');
                                $('.email-wrapper').append(email_err_msg);

                            } else {}

                        });

                    }

                }

            });

        });


    }



    function delete_person() {

        $('#btn-delete').click(function() {

            Swal.fire({

                title: 'Are you sure?',
                html: 'Delete <b>{{ $person["name"] }}</b>',
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
                    });


                    xhr = $.ajax({
                        url: "{{ route('person.delete') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: "{{ $person['id'] }}",
                        },

                        beforeSend: function() {

                            if (xhr != null) {
                                xhr.abort();
                            }

                        },

                        success: function(data) {

                            Swal.fire({
                                title: 'Deleted!',
                                html: 'Person been deleted.',
                                icon: 'success',
                                allowOutsideClick: false,

                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.replace("{{ route('person') }}");
                                }

                            });

                        }

                    });

                }
            });

        });

    }



    function delete_organization() {

        $(document).off('click', '#org-delete').on('click', '#org-delete', function(e) {

            var trigger_btn = e.target,
                parent_tr = $(trigger_btn).closest('tr').get(0);

            let org_id = $(this).data('id'),
                person_id = "{{ $person['id'] }}",
                org_name = $(parent_tr).find("td:first").text();

            Swal.fire({

                title: 'Are you sure?',
                html: 'Delete <b>' + org_name + '</b>',
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
                        url: "{{ route('person.delete-organization') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            person_id: person_id,
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
                                html: 'Your file has been deleted.',
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



    function add_org() {

        $('#add-organization-form').submit(function(e) {

            e.preventDefault();

            let formData = new FormData(this);
            let frm_btn = '#add-org-btn';

            formData.append('person_id', "{{ $person['id'] }}");

            xhr = $.ajax({
                url: "{{ route('person.add-organization') }}",
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

                    $("#organization").val('').trigger('change');

                    setTimeout(() => {

                        $('#modal-add-org').modal('hide');
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

        delete_person();

        delete_organization();

        btn_add_org();

        add_org();


    })
</script>

@endsection