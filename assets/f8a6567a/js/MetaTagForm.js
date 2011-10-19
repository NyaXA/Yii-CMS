$(function()
{
    var $object_id     = $('#MetaTag_object_id');
    var $model_id      = $('#MetaTag_model_id');
    var $meta_tag_id   = $('#MetaTag_id');
    var $static_value  = $('#MetaTag_static_value');
    var $dynamic_value = $('#MetaTag_dynamic_value');
    var $meta_tag      = $('#MetaTag_tag');
    var $submit        = $('#meta_tag_submit');
    var $meta_tag_id   = $('#MetaTag_id');


    if ($model_id.val())
    {
        loadModelObjects($model_id.val(), $meta_tag_id.val());
    }
    else
    {
        $meta_tag.attr('disabled', true);
        blockObjectIdElement();
    }

    $model_id.change(function()
    {
        var model_id = $(this).val();

        if (model_id)
        {
            loadModelObjects(model_id);
        }
        else
        {
            $meta_tag.find('option:eq(0)').attr("selected", true);
            clearFieldsVal();
            blockObjectIdElement();
        }
    });


    $object_id.change(function()
    {
        var object_id = Boolean($(this).val());

        if (!object_id)
        {
            $meta_tag.find('option:eq(0)').attr("selected", true);
            clearFieldsVal();
        }

        $meta_tag.attr('disabled', !object_id);
    });


    $meta_tag.change(function()
    {
        var meta_tag = $meta_tag.val();

        if (!meta_tag)
        {
            clearFieldsVal();
            return;
        }

        var params = {
            'model_id'  : $model_id.val(),
            'object_id' : $object_id.val(),
            'tag'       : meta_tag
        };

        $.getJSON('/main/metaTagAdmin/GetMetaTagData', params, function(data)
        {
            if (data)
            {
                $static_value.val(data.static_value);
                $dynamic_value.val(data.dynamic_value);
                $meta_tag_id.val(data.id);

                $submit.attr('value', 'Сохранить');
            }
            else
            {
                $submit.attr('value', 'Создать');
                clearFieldsVal();
            }
        })
    });


    function blockObjectIdElement()
    {
        $object_id.html('<option>выберите модель</option>');
        $object_id.attr('disabled', true);
    }


    function loadModelObjects(model_id, object_id)
    {
        $.getJSON('/main/MetaTagAdmin/getModelObjects', {'model_id' : model_id, 'object_id' : object_id}, function(objects)
        {
            var options = "<option value=''>не выбран</option>";

            if (objects)
            {
                for (var id in objects)
                {
                    options+= "<option value='" + id + "'>" + objects[id] + "</option>";
                }

                $object_id.html(options);
                $object_id.attr('disabled', false);
            }
        });
    }


    function clearFieldsVal()
    {
        var fields = [$static_value, $dynamic_value, $meta_tag_id];

        for (var i in fields)
        {
            fields[i].val("")
        }
    }
});


