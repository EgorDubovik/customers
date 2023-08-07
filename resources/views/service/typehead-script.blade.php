<script src="{{ URL::asset('assets/plugins/typehead/jquery.typeahead.js')}}"></script>
<script>
$(document).ready(function () {
    $('.js-typeahead').typeahead({
        minLength: 0,
        order: "asc",
        maxItem: 15,
        accent: true,
        searchOnFocus : true,
        template: "{\{title\}}, {\{price\}}",
        source: {
            data: @json($services)
        },
        display:'title',
        callback: {
            onClickAfter: function (node, a, item, event) {
                event.preventDefault();
                $('#price').val(item.price);
                $('#description').val(item.description);
                $('#price').focus();
                $('#price').select();
            },
            
        },
        debug: true
    });
});
</script>