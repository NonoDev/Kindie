$(document).ready(function(){
    $('.morphsearch-input').keyup(function()
    {
        searchText = $(this).val();
        //if (this.value.length >= 3 || this.value == '')
        //{
            $.ajax({
                type: "GET",
                url: "/app_dev.php/search",
                dataType: "json",
                data: {searchText : searchText},
                success : function(response)
                {
                    console.log(response);
                }
            });
        //}
    });
});