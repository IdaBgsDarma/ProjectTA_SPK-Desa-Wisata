@extends('layouts.layouts')
@section('content')
    <div class="services_section layout_padding">
        <div class="container">
            <p class="services_text1 center-text">Desa</p>
            <h1 class="services_taital center-text">Per {{ $kabupaten->nama }}</h1>
            <div class="services_section_2">
                <div class="row" id="data">
                    @include('desaPagination')
                </div>
            </div>
        </div>
        <div style="margin: auto;width: 50%;text-align:center">
            <a href="javascript:void(0);" id="btn_loadmore" style="color:black">Load more</a>
            <br>
            <div class="ajax-load text-center" style="display:none">
                Loading
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">
    var page = 1;
    $('#btn_loadmore').on('click',function(){
        $('#btn_loadmore').prop('disabled', true);
        page++
        loadMoreData(page);
    });
    function loadMoreData(page){
        $.ajax({
                url: '?page=' + page,
                type: "get",
                beforeSend: function(){
                    $('.ajax-load').show();
                }
            })
            .done(function(data){
                if(data.html == ""){
                    $('#btn_loadmore').remove();
                }
                $('#btn_loadmore').prop('disabled', false);
                $('.ajax-load').hide();
                $("#data").append(data.html);
        })
    }
</script>
@endpush
