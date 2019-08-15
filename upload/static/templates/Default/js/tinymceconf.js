tinymce.init({
    mode : "exact",
    elements : "firewall_core[conf_params][exit_message], core[conf_params][maintenance_exit_message]",
    theme: "modern",
    skin: 'lightgray',
    entity_encoding: "raw",
    convert_urls : false,
    verify_html : false,
    relative_urls : false,
    height : 300,
    width: '100%',
    plugins: [
        "advlist autolink lists link image charmap preview hr anchor pagebreak autoresize",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "paste textcolor"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview media | forecolor backcolor | placeholder",
    toolbar2: "",
    setup: function(editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });

        editor.addButton('placeholder', {
            type: 'menubutton',
            text: 'Placeholder',
            icon: false,
            menu: [
                {text: 'Captcha', onclick: function() {editor.insertContent('%Captcha%');}},
                {text: 'Visitor IP', onclick: function() {editor.insertContent('%VisitorIP%');}},
                {text: 'Time', onclick: function() {editor.insertContent('%BlockedTime%');}},
                {text: 'Block Reason', onclick: function() {editor.insertContent('%BlockReason%');}}
            ]
        });
    },
    image_advtab: true,
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}
    ]
});