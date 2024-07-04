@extends('crudbooster::admin_template')


@section('content')
    <div class="container-fluid">
			<div class="paginate">
				<div class="row items" id="container"></div>
    				
			</div>
		</div>

@endsection

@push('bottom')
<script>
loadFrame();

function loadFrame(){
	name = "{!! $cctv->name !!}";
	deskripsi = "{!! $cctv->display_name !!}";
	milik = "{!! $cctv->owner !!}";

	 var recode = "";
	 recode += '<div class="col-sm-4" style="background-color:lavenderblush;"><br>';
 	 recode += '<iframe id="ifrm-'+name+'" frameBorder="0" src="https://streaming.indera.id:4433?'+name+'&'+ deskripsi +'&'+milik+'" allowfullscreen allow="autoplay" scrolling="no" style="width:100%;height:300px"></iframe>';

	 recode +=	'<br>';
 	 recode +='</div>';

    
	$("#container").append(recode);	
	
  }
</script>

@endpush