$.widget('cmsUI.fileupload', $.blueimpUI.fileupload, {
    options:{
        version:'1.0',
        existFilesUrl:''
    },
    _create:function()
    {

        $.blueimpUI.fileupload.prototype._create.call(this); //base constructor

        this._loadExistingFiles();
        this._sortableInit();
        this._jeditableInit();
        this._adaptationToBrowser();

        // Open download dialogs via iframes,
        // to prevent  aborting current uploads:
        //        uploader.find('.files a:not([target^=_blank])').live('click', function (e) {
        //            e.preventDefault();
        //            $(\"<iframe style='display:none;'></iframe>\")
        //                .attr('src', this.href)
        //                .appendTo('body');
        //        });
    },
    _loadExistingFiles:function()
    {
        var widget = this;

        $.getJSON(this.options.existFilesUrl, {}, function(files)
        {
            widget._adjustMaxNumberOfFiles(-files.length);
            widget._renderDownload(files)
                .appendTo(widget.element.find('.files'))
                .fadeIn(500, function()
                {
                    // Fix for IE7 and lower:
                    $(this).show();
                });
        });
    },
    _sortableInit:function()
    {
        var widget = this;
        this.element.find('.files tbody').sortable({
            handle:'.dnd-handler',
            update:function(event, ui)
            {
                $.post(widget.options.sortableSaveUrl, $(this).sortable('serialize'));
            }
        });

    },
    _jeditableInit:function()
    {
        this.element.delegate('.thumb-edit', 'click', function()
        {
            var link = $(this),
                item = link.closest('td'),
                field = $('.editable', item),
                action = link.attr('href'),
                options = {
                    type:field.data('editable-type'),
                    rows:3,
                    cols:20,
                    submitdata:{
                        id:link.closest('tr').attr('id').split('_')[1],
                        model:'FileManager',
                        attribute:field.data('attr')
                    },
                    onblur:'submit',
                    callback:function()
                    {
                        link.css({display:'inline'})
                    }
                };

            field.children('span').editable(action, options).click();

            return false;
        });
    },
    _adaptationToBrowser:function()
    {
        //if browser not support dnd
        if (!Modernizr.draganddrop)
        {
            this.element.find('.drop-zone').remove();
        }
    }
});
