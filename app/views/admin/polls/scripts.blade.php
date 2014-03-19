<script type="text/javascript">
$(document).ready(function() {
	$('body').on('click', '.add' , function(){
		$('table#answers-table').append('<tr> <td> {{Form::text("answers[]", null, array("id" => "answers", "placeholder"=>"Отговор", "required" => "required"))}} <small class="error">Моля добавете отговор</small> </td> <td> <a class="dark remove"><i class="fi-x"></i></a> </td> </tr>');
	});
	$('body').on('click', '.add-ajax' , function(){
		$.ajax({
	        type: "POST",
	        url: "{{URL::to('admin/answers')}}",
	        data: {poll_id : "{{$poll->id}}"},
	        success: function(data) {
				$('table#answers-table').append('<tr id='+data.id+'> <td> {{Form::text("answers[]", null, array("class" => "update-ajax", "id" => "answer-'+data.id+'", "placeholder"=>"Отговор", "required" => "required"))}} <small class="error">Моля добавете отговор</small> </td> <td> <a class="dark remove-ajax"><i class="fi-x"></i></a> </td> </tr>');
	        },  
	        dataType: "json"
	    });
	});

	$( "body" ).on('change', '.update-ajax', function() {
		var id = $(this).closest('tr').attr('id');
		$.ajax({
			url: '{{URL::to("admin/answers/'+id+'")}}',
			data: { title: $(this).val()},
			type: 'PUT',
			dataType: 'json',
			success: function(result) {
			}
		});
	});
	$('table#answers-table').on('click', '.remove' , function(){
 		if(confirm('Сигурни ли сте, че желаете да изтриете този отговор?'))
			$(this).closest('tr').remove();
	});
	
	$('table#answers-table').on('click', '.remove-ajax' , function(){
 		if(confirm('Сигурни ли сте, че желаете да изтриете този отговор?'))
 		{
 			var id = $(this).closest('tr').attr('id');
		  	$.ajax({
			    url: "{{URL::to('admin/answers/"+id+"')}}",
			    type: 'DELETE',
			    dataType: 'json',
			    success: function(result) {
			    	if(result.success)
			    		$('table#answers-table tr#' + id).remove();
			    }
		  	});
 		}	
	});
	$('table#answers-table').on('click', '.vote' , function(){
		var id = $(this).closest('tr').attr('id');
	  	$.ajax({
		    url: "{{URL::to('api/polls/'.$poll->id)}}",
		    type: 'PUT',
			data: { answer: id},
		    dataType: 'json',
		    success: function(result) {
		    		
		    }
	  	});
 		
	});

});
</script>
