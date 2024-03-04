@extends('template')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/powerbi/powerbi.css') }} "/>
@stop


@section('section')

	<div class='div' style="margin:0px;padding:0px;overflow:hidden">
		<iframe src="https://app.powerbi.com/view?r=eyJrIjoiZGE5ZjI4NGUtNDAyOS00MmI4LTgyMzgtMTNiNzUwOWZhMDU1IiwidCI6ImUyYTQxNTVkLTg1NGMtNDYzYS04ZWE2LWFkNDYzNjYxMjJiZCIsImMiOjR9"></iframe>	
	</div>

@stop


@section('script')


    <script type="text/javascript">
		$(document).ready(function(){

			var carpeta = $("#carpeta").val();
			//$("iframe").attr("src","");

    		//setTimeout(attr, 30000);
		});

		function attr(){
		    $("iframe").attr("src","");
		}
    </script> 
@stop