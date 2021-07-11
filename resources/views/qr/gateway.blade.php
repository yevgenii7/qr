@extends('app')

@section('content')
<div class="row margin-top-30">
    <div class="col-md-12 col-sm-12">
        <h3>Подождите, сейчас вы будете переадресованы на целевой ресурс:</h3>
        <p type="button" class="btn btn-link qr-gateway">{{$redirect_url}}</p>
        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
    </div>
</div>

<script type="text/javascript">
    var url = "<?php echo $redirect_url; ?>";
    
    function redirect(){
        window.location.href = url;
    }
    
    setTimeout('redirect()',4000);
</script>
@endsection

