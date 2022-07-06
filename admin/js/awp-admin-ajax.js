jQuery(document).ready(function($) {

	const $form  = $('#courses');
    const $responseContainer = $('#ajax_response');

    $form.submit(function(e){
        e.preventDefault();
		var selected_courses = [];

		$('#courses input:checked').each(function() {
			selected_courses.push($(this).attr('name'));
		});
        
        $.post({
            url : ajax_data.url, // aqui é url que passamos como atributo extra com a função wp_localize_script()
            data : {
                action : 'add_new_courses', // este é o nome da nossa função backend
                selected_courses: selected_courses,
				unsynchronized_courses: ajax_data.unsynchronized_courses
            },
			success: function(res) {
				console.log(JSON.parse(res));
                const html = JSON.parse(res);
                $responseContainer.html(html); // aqui nós acrescentamos o retorno da req. no container
            }
        })
    })
});
