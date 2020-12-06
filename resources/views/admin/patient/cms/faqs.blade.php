@extends('admin.layouts.main')

@section('styles')

@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="alert-text errorMessages" style="display:none">
            <div class="alert alert-danger">
                <ul class="ajaxErrorMessages">
                </ul>
            </div>
        </div>
        <div class="alert-text successMessages" style="display:none">
            <div class="alert alert-success">
                <ul class="ajaxSuccessMessages">
                </ul>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="row">
            <div class="col-lg-12">

                @if ( count( $errors ) > 0 )
                <div class="alert alert-light alert-elevate" role="alert">
                    @foreach ($errors->all() as $error)
                        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i>
                            <div class="alert-text">{{ $error }}</div>
                        </div>
                    @endforeach
                </div>
                @endif
                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{ $title }}
                            </h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-body">
                                <div class="accordion accordion-light  accordion-toggle-arrow" id="accordionExample5">
                                    <div class="card">
                                        <div class="card-header" id="headingTwo5">
                                            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo5" aria-expanded="false" aria-controls="collapseTwo5">
                                                <i class="flaticon-squares"></i>FAQ'S
                                            </div>
                                        </div>
                                        <div id="collapseTwo5" class="collapse" aria-labelledby="headingTwo5" data-parent="#accordionExample5">
                                            <div class="card-body">
                                                <table class="table">
                                                    @if(count($faqs) > 0)
                                                        @foreach($faqs as $faq)
                                                            @php $faqa = json_decode($faq->faq, true) @endphp
                                                    <tr>
                                                        <td>{{ $faqa['heading'] }}</td>
                                                        <td>
                                                            <a href="javascript:;" id="editFaq" data-id="{{ $faq->id }}" data-url="{{ route('admin.patient.cms.faqs.edit') }}" data-token="{{ csrf_token() }}"><i class="flaticon-edit"></i></a>
                                                            <a href="{{ route('admin.patient.cms.faqs.delete', ['id' => $faq->id]) }}"><i class="fa fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                        @endforeach
                                                    @endif
                                                    <tr>
                                                        <td><button type="button" class="btn btn-primary" id="addFaq">Add</button></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--end::Portlet-->
            </div>
        </div>
    </div>
    <div class="modal fade" id="saveFaqModal" tabindex="-1" role="dialog" aria-labelledby="faqModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="faqModal">Section one</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" class="form-horizontal" action="{{ route('admin.patient.cms.faqs.save') }}" enctype="multipart/form-data" id="saveFaq">
                    {{ csrf_field() }}
                    <div class="modal-body modalRows">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="./assets/js/demo1/pages/crud/forms/validation/form-controls.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).on('click', '#addFaq', function(){
        $("#saveFaqModal").modal('show');

        $('.modalRows').empty();

        $('.faqRows').empty();

        $('.modalRows').append(''
            +'<div class="form-group row">'
                +'<div class="col-md-12">'
                    +'<label>Faq</label>'
                    +'<input type="text" name="heading" class="form-control" placeholder="Faq" value="">'
                +'</div>'
            +'</div>'
            +'<div class="form-group">'
                +'<i class="fa fa-plus" id="addAnswers"> Add Faq</i>'
            +'</div>'
            +'<div class="faqRows"></div>'
            +'<div class="form-group">'
                +'<input type="hidden" name="id" value="">'
                +'<button type="submit" class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u"><i class="fa fa-plus"></i> Save</button>'
            +'</div>'
        );
    });

    $(document).on('click', '#addAnswers', function(){
        var id = Math.floor(Math.random() * 100);

        $('.faqRows').prepend(''
            +'<div class="faq_'+id+'">'
                +'<div class="form-group row">'
                    +'<div class="col-md-12">'
                        +'<label>FAQ Question</label>'
                        +'<input type="text" name="questions[]" class="form-control" placeholder="Question">'
                    +'</div>'
                +'</div>'
                +'<div class="form-group row">'
                    +'<div class="col-md-12">'
                        +'<label>Faq Answer</label>'
                        +'<textarea name="answers[]" class="form-control" placeholder="Answers"></textarea>'
                    +'</div>'
                +'</div>'
                +'<div class="form-group">'
                    +'<i class="fa fa-minus" id="remove_faq" data-id="'+id+'"> Remove Faq</i>'
                +'</div>'
            +'</div>'
        );
    });

    $(document).on('click', '#remove_faq', function(){
        if(confirm('Do you really want to remove this faq?')){
            $('.faq_'+$(this).attr('data-id')+'').remove();
        }
    });

    $(document).on('submit', '#saveFaq', function (e){
        e.preventDefault();

        var messages = '';

        $('.errorMessages').hide();
        $('successMessages').hide();

        $.ajax({
            url : $(this).attr('action'),
            method : "post",
            data : new FormData($(this)[0]),
            processData: false,
            contentType: false,

            success:function(response){
                if(response.status == 1){
                    $('.successMessages').show();

                    messages = '<li>'+response.message+'</li>';

                    $('.ajaxSuccessMessages').html(messages);

                    window.location.replace("{{ route('admin.patient.cms.faqs') }}");
                }else if(response.status == 0){
                    $('.errorMessages').show();

                    messages = '<li>'+response.message+'</li>';

                    $('.ajaxErrorMessages').html(messages);
                }
            },
            error: function(response, status, error){
                $('.errorMessages').show();

                $('.ajaxErrorMessages').empty();

                result = $.parseJSON(response.responseText);

                $.each(result.errors, function(key, value){
                    $('.ajaxErrorMessages').append('<li>'+value+'</li>');  
                });
            }
        });
    });

    $(document).on('click', '#editFaq', function (e){
        e.preventDefault();

        $("#saveFaqModal").modal('show');

        $('.modalRows').empty();

        $('.faqRows').empty();

        var messages = '';

        $.ajax({
            url : $(this).attr('data-url'),
            method : 'post',
            data : {
                _token : $(this).attr('data-token'),
                id : $(this).attr('data-id'),
            },

            success:function(response){
                if(response.status == 1){
                    $('.modalRows').prepend(response.data);

                    var data = $.parseJSON(response.data['faq']);

                    $('.modalRows').append(''
                        +'<div class="form-group row">'
                            +'<div class="col-md-12">'
                                +'<label>Faq</label>'
                                +'<input type="text" name="heading" class="form-control" placeholder="Faq" value="'+data.heading+'">'
                            +'</div>'
                        +'</div>'
                        +'<div class="form-group">'
                            +'<i class="fa fa-plus" id="addAnswers"> Add Faq</i>'
                        +'</div>'
                        +'<div class="faqRows"></div>'
                        +'<div class="form-group">'
                            +'<input type="hidden" name="id" value="'+response.data['id']+'">'
                            +'<button type="submit" class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u"><i class="fa fa-plus"></i> Save</button>'
                        +'</div>'
                    );

                    $.each(data.questions, function (key, value){
                        var id = Math.floor(Math.random() * 100);

                        $('.faqRows').prepend(''
                            +'<div class="faq_'+id+'">'
                                +'<div class="form-group row">'
                                    +'<div class="col-md-12">'
                                        +'<label>FAQ Question</label>'
                                        +'<input type="text" name="questions[]" class="form-control" placeholder="Question" value="'+value+'">'
                                    +'</div>'
                                +'</div>'
                                +'<div class="form-group row">'
                                    +'<div class="col-md-12">'
                                        +'<label>Faq Answer</label>'
                                        +'<textarea name="answers[]" class="form-control" placeholder="Answers">'+data.answers[key]+'</textarea>'
                                    +'</div>'
                                +'</div>'
                                +'<div class="form-group">'
                                    +'<i class="fa fa-minus" id="remove_faq" data-id="'+id+'"> Remove Faq</i>'
                                +'</div>'
                            +'</div>'
                        );
                    });

                    console.log(data);
                }else if(response.status == 0){
                    $('.errorMessages').show();

                    messages = '<li>'+response.message+'</li>';

                    $('.ajaxErrorMessages').html(messages);

                    $('#saveFaqModal').hide();
                }
            },
            error: function(response, status, error){
                $('.errorMessages').show();

                $('.ajaxErrorMessages').empty();

                response = $.parseJSON(response.responseText);

                $.each(response.errors, function(key, value){
                    $('.ajaxErrorMessages').append('<li>'+value+'</li>'); 
                });
            }
        });
    });
</script>
@endsection