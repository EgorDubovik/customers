<script src="{{ URL::asset('assets/plugins/autocomplete/jquery.autocompleter.js')}}"></script>
<script>
$(document).ready(function () {
    $('.js-typeahead').autocompleter({
        source: @json($services),
        empty: false,
        focusOpen: true,
        limit: 10,
        customLabel : 'title',
        callback: function (index, selected) {
            let wire = window.Livewire.getByName("appointment.services")[0];
            wire.title = selected.title;
            wire.price = selected.price;
            wire.description = selected.description;
            $('#price').val(selected.price);
            $('#description').val(selected.description);
            $('#price').focus();
            $('#price').select();
        }
    });
    // $('.js-typeahead').typeahead({
    //     minLength: 0,
    //     // order: "asc",
    //     maxItem: 15,
    //     accent: true,
    //     searchOnFocus : true,
    //     template: "{\{title\}}, {\{price\}}",
    //     source: {
    //         data: @json($services)
    //     },
    //     display:'title',
    //     callback: {
    //         onClickAfter: function (node, a, item, event) {
    //             event.preventDefault();
    //             let wire = window.Livewire.getByName("appointment.services")[0];
    //             wire.title = item.title;
    //             wire.price = item.price;
    //             wire.description = item.description;
    //             $('#price').val(item.price);
    //             $('#description').val(item.description);
    //             $('#price').focus();
    //             $('#price').select();
    //         },
            
    //     },
    //     debug: true
    // });
});
</script>