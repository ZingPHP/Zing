$(document).on("click", ".rm-blacklist", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var me = $(this);
        $.ajax({
            type: "get",
            url: "./blacklist.php?ajax=1&action=remove&id=" + id,
            dataType: "json",
            success: function(data){
                if(data["good"]){
                    me.closest("tr").remove();
                }
            }
        });
   });
   $(document).on("click", ".add-url", function(e){
        e.preventDefault();
        var good = false;
        var url = prompt("Please enter the URL:");
        if(url !== null){
            var reason = prompt("Please enter a reason:");
            if(reason !== null){
                good = true;
            }
        }
        if(good){
            var me = $(this);
            $.ajax({
                type: "get",
                url: "./blacklist.php",
                data: {
                    url: url,
                    reason: reason,
                    ajax: 1,
                    action: "add"
                },
                dataType: "json",
                success: function(data){
                    if(data["good"]){
                        window.location = window.location.toString();
                    }else{
                        alert("Invalid URL");
                    }
                }
            });
        }
    });

