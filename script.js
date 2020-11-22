$(document).ready(function() {

    $(".change").click(function(event) {
        var body_book = $(this).parent();
        var id = body_book.children('.id_book').val();
        $(".update_form").append('<input type="hidden" name="id_pook" value="' + id + '">').show();

        // $.post("/controllers.php", {
        //           action: "update_form",
        //           id: id
        //       }, function (data) {
        //       	console.log(JSON.parse(data));
        //       });
        $.ajax({
            url: '/controllers.php',
            type: 'POST',
            data: ({ action: "update_form", id: id }),
            dataType: 'text',
            cache: 'false',
            success: function(data) {
                var pos = data.indexOf('}');
                var str = data.substr(pos + 1);
                var mas = str.split(",");
                var title = mas[0];
                var date = mas[1];
                var author = mas[2];
                var ganre = mas[3];
                var dscr = mas[4];
                console.log(data);
                $("#title_update").val(title);
                $("#date_update").val(date);
                $("#author_update").val(author);
                $("#ganre_update").val(ganre);
                $("#description_update").val(dscr);
                // console.log(title);

                $(".img").append('<img class="other_mg" src = "/upload/' + id + '.jpg">');

            }
        });
    });

    $('.cancel_update').click(function(event) {
        $(".other_mg").remove();
        $(".update_form").hide();
    });
    $('.cancel_update').click(function(event) {
        $(".create_form").hide();
    });
    $('.click_add').click(function(event) {
        $(".create_form").show();
    });
    $('.delete').click(function(event) {
        var body_book = $(this).parent();
        var id = body_book.children('.id_book').val();
        // alert(id);
        if (confirm("Вы действительно хотите удалить это?")) {
            $.post("/controllers.php", {
                action: "delete",
                id: id
            }, function(data) {
                console.log(data);
            });
        }
        location.reload(true);
    });
    $('.aut').change(function(event) {
        var id = $(this).val();
        $.post("/controllers.php", {
                action: "aut",
                id: id
            }, function(data) {
            	var pos = data.indexOf('}');
                var str = data.substr(pos + 1);
                var mas = str.split("/");
                var result_out = "";
                var item = [];
                for(var i=0; i<mas.length; i++){
                	if (i==mas.length-1) continue;
                	item = mas[i].split(",")
                	console.log(item);
                	result_out += "<div class='book' style='width:500px'>"+
                "<img class='book-img-top' src='/upload/"+item[0]+".jpg' style='width:100%'>"+
                "<div class='item-body'>"+
                "    <div class='item-title'>"+item[1]+"</div>"+
                "    <div class='item-text'>"+item[2]+"</div>"+
                "    <div class='item-genre'>"+item[3]+"</div>"+
                "    <div class='item-author'>"+item[4]+"</div>"+
                "    <div class='item-date'>"+item[5]+"</div>"+
                "    <button class='btn btn-primary change'>Изменить</button>"+
                "    <button class='btn btn-danger delete'>Удалить</button>"+
                "    <input type = 'hidden' class='id_book' value='"+item[0]+"'>"+
                "</div></div>";
              }
              $(".books").empty();
              $(".books").append(result_out);
        });
    });
     $('.gen').change(function(event) {
        var id = $(this).val();
        $.post("/controllers.php", {
                action: "gen",
                id: id
            }, function(data) {
            	var pos = data.indexOf('}');
                var str = data.substr(pos + 1);
                var mas = str.split("/");
                var result_out = "";
                var item = [];
                for(var i=0; i<mas.length; i++){
                	if (i==mas.length-1) continue;
                	item = mas[i].split(",")
                	console.log(item);
                	result_out += "<div class='book' style='width:500px'>"+
                "<img class='book-img-top' src='/upload/"+item[0]+".jpg' style='width:100%'>"+
                "<div class='item-body'>"+
                "    <div class='item-title'>"+item[1]+"</div>"+
                "    <div class='item-text'>"+item[2]+"</div>"+
                "    <div class='item-genre'>"+item[3]+"</div>"+
                "    <div class='item-author'>"+item[4]+"</div>"+
                "    <div class='item-date'>"+item[5]+"</div>"+
                "    <button class='btn btn-primary change'>Изменить</button>"+
                "    <button class='btn btn-danger delete'>Удалить</button>"+
                "    <input type = 'hidden' class='id_book' value='"+item[0]+"'>"+
                "</div></div>";
              }
              $(".books").empty();
              $(".books").append(result_out);
        });
    });
    $("#btn_searche").click(function(event) {
    	var search = $("#fld_search").val();
    	$.post("/controllers.php", {
                action: "search",
                search: search
            }, function(data) {
            	var pos = data.indexOf('}');
                var str = data.substr(pos + 1);
                var mas = str.split("/");
                var result_out = "";
                var item = [];
                for(var i=0; i<mas.length; i++){
                	if (i==mas.length-1) continue;
                	item = mas[i].split(",")
                	console.log(item);
                	result_out += "<div class='book' style='width:500px'>"+
                "<img class='book-img-top' src='/upload/"+item[0]+".jpg' style='width:100%'>"+
                "<div class='item-body'>"+
                "    <div class='item-title'>"+item[1]+"</div>"+
                "    <div class='item-text'>"+item[2]+"</div>"+
                "    <div class='item-genre'>"+item[3]+"</div>"+
                "    <div class='item-author'>"+item[4]+"</div>"+
                "    <div class='item-date'>"+item[5]+"</div>"+
                "    <button class='btn btn-primary change'>Изменить</button>"+
                "    <button class='btn btn-danger delete'>Удалить</button>"+
                "    <input type = 'hidden' class='id_book' value='"+item[0]+"'>"+
                "</div></div>";
              }
              $(".books").empty();
              $(".books").append(result_out);
        });
    });
});