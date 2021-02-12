@extends('layout.app')
@section('title', 'Create Person')

@section('content')

<div class="card col-8 offset-2">

    <div class="card-body">
        <div class="card-title h4">
            <span class="fas fa-fw fa-user-plus"></span> Create Person

            <a href="{{ route('person') }}" class="btn btn-secondary float-end"> <span class="fas fa-fw fa-long-arrow-alt-left"></span> Back</a>

        </div>

        <hr>

        <small class="text-danger">Fields with * are required</small>

        <form method="post" class="mt-3" id="create-person-form">

            @csrf

            <div class="row g-2">

                <div class="col-md">

                    <div class="form-floating mb-3 name-wrapper">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" autofocus>
                        <label for="name">Full Name *</label>
                    </div>
                </div>

                <div class="col-md">

                    <div class="form-floating email-wrapper">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                        <label for="email">Email *</label>
                    </div>
                </div>

            </div>

            <div class="col-6 offset-3">

                <label for="organization" class="form-label">Organization</label>

                <select class="form-select" id="organization" name="organization[]" multiple="multiple">
                    <option disabled>Select Organization</option>
                    @foreach($organizations as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach

                </select>


            </div>


            <button type="submit" class="btn btn-success" id="btn-submit"> <span class="fas fa-fw fa-save"></span> Submit</button>
        </form>

    </div>
</div>


@endsection

@section('scripts')

<script>

    var xhr = null;

    function create() {

        $('#create-person-form').submit(function(e) {

            e.preventDefault();

            let formData = new FormData(this);
            let frm_btn = '#btn-submit';

            xhr = $.ajax({
                url: "{{ route('person.store') }}",
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

                    $("#organization").val('').trigger('change');
                    $('#create-person-form')[0].reset();
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

    $(function() {

        $('#organization').select2({
            placeholder: "Select Organization",
            allowClear: true
        });

        create();
    })
</script>

@endsection